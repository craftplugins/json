<?php

namespace augmentations\craft\json\gql;

use craft\gql\base\Directive;
use craft\gql\GqlEntityRegistry;
use craft\helpers\Json;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class JsonDecodeDirective
 *
 * @package augmentations\craft\json\gql
 */
class JsonDecodeDirective extends Directive
{
    /**
     * @return \GraphQL\Type\Definition\Directive
     */
    public static function create(): GqlDirective
    {
        if ($type = GqlEntityRegistry::getEntity(self::name())) {
            return $type;
        }

        return GqlEntityRegistry::createEntity(
            static::name(),
            new self([
                "name" => static::name(),
                "description" =>
                    "Decodes a JSON string and adds the decoded result inline.",
                "locations" => [DirectiveLocation::FIELD],
            ])
        );
    }

    /**
     * @return string
     */
    public static function name(): string
    {
        return "jsonDecode";
    }

    /**
     * @param mixed                                $source
     * @param mixed                                $value
     * @param array                                $arguments
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     *
     * @return mixed
     */
    public static function apply(
        $source,
        $value,
        array $arguments,
        ResolveInfo $resolveInfo
    ) {
        if (is_string($value) && !empty($value)) {
            $value = Json::decodeIfJson($value, false);
        }

        return $value;
    }
}
