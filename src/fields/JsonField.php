<?php

namespace augmentations\craft\json\fields;

use augmentations\craft\json\assetbundles\jsonfield\JsonFieldAsset;
use augmentations\craft\json\validators\JsonValidator;
use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\gql\GqlEntityRegistry;
use craft\gql\TypeLoader;
use craft\helpers\Html;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\web\View;
use GraphQL\Type\Definition\Type;
use MLL\GraphQLScalars\MixedScalar;
use yii\db\Schema;

/**
 * Class JsonField
 *
 * @package augmentations\craft\json\fields
 * @property-read string[]                      $elementValidationRules
 * @property-read string                        $contentColumnType
 * @property-read null|string                   $settingsHtml
 * @property-read \GraphQL\Type\Definition\Type $contentGqlType
 */
class JsonField extends Field
{
    /**
     * @var bool
     */
    public $prettify = true;

    /**
     * @return string
     */
    public static function displayName(): string
    {
        return "JSON";
    }

    /**
     * @return string
     */
    public function getContentColumnType(): string
    {
        // N.B. Craft does not officially support JSON yet
        // https://github.com/craftcms/cms/issues/7951
        return Schema::TYPE_TEXT;
    }

    /**
     * @return string[]
     */
    public function getElementValidationRules(): array
    {
        return [JsonValidator::class];
    }

    /**
     * @return \GraphQL\Type\Definition\Type
     */
    public function getContentGqlType(): Type
    {
        $typeName = "Mixed";

        if ($type = GqlEntityRegistry::getEntity($typeName)) {
            return $type;
        }

        $type = GqlEntityRegistry::createEntity(
            $typeName,
            new MixedScalar(["name" => $typeName])
        );

        TypeLoader::registerType($typeName, function () use ($type) {
            return $type;
        });

        return $type;
    }

    /**
     * @param mixed                             $value
     * @param \craft\base\ElementInterface|null $element
     *
     * @return mixed
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($this->prettify && !empty($value)) {
            if (is_string($value)) {
                $value = Json::decodeIfJson($value, false);
            }

            $value = Json::encode(
                $value,
                JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_SLASHES |
                    JSON_UNESCAPED_UNICODE
            );

            $value = StringHelper::replace($value, "    ", "  ");
        }

        return parent::normalizeValue($value, $element);
    }

    /**
     * @param mixed                             $value
     * @param \craft\base\ElementInterface|null $element
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getInputHtml(
        $value,
        ElementInterface $element = null
    ): string {
        $view = Craft::$app->getView();

        $view->registerAssetBundle(JsonFieldAsset::class);

        $id = Html::id($this->handle);
        $namespacedId = $view->namespaceInputId($id);

        $view->registerJs(
            'document.documentElement.classList.add("js")',
            View::POS_HEAD
        );

        return $view->renderTemplate("json/input", [
            "id" => $id,
            "name" => $this->handle,
            "namespacedId" => $namespacedId,
            "value" => $value,
        ]);
    }

    /**
     * @return string|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate("json/settings", [
            "field" => $this,
        ]);
    }
}
