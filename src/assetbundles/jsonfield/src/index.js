import loader from "@monaco-editor/loader";
import "./index.scss";

const minHeight = 120;

loader.init().then((monaco) => {
  monaco.editor.defineTheme("craft", {
    base: "vs",
    inherit: true,
    rules: [
      { background: "FFFFFF" },
      { token: "string.key.json", foreground: "1F61A0" },
      { token: "string.value.json", foreground: "D64292" },
    ],
    colors: {
      "editor.foreground": "#555",
      "editor.background": "#fff",
      "editorGutter.background": "#f3f7fc",
      "editorCursor.foreground": "#555",
      "editor.lineHighlightBackground": "#fafafa",
      "editorLineNumber.foreground": "#606d7b",
      "editorIndentGuide.background": "#e0e4e9",
    },
  });

  const containers = document.querySelectorAll(".json-field-container");

  containers.forEach((container) => {
    const textarea = container.querySelector(".json-field-value");

    const dom = document.createElement("div");
    dom.style.height = minHeight + "px";
    textarea.parentNode.insertBefore(dom, textarea);

    const editor = monaco.editor.create(dom, {
      language: "json",
      value: textarea.value,

      // Options
      automaticLayout: true,
      fontFamily: '"SFMono-Regular", Consolas, Menlo, monospace',
      fontSize: "14px",
      lineHeight: "22px",
      minimap: { enabled: false },
      overviewRulerLanes: 0,
      scrollbar: { alwaysConsumeMouseWheel: false },
      scrollBeyondLastLine: false,
      tabSize: 2,
      theme: "craft",
      wordWrap: "on",
      wrappingIndent: "indent",
    });

    editor.onDidChangeModelContent(() => {
      textarea.value = editor.getValue();
    });

    editor.onDidContentSizeChange(() => {
      const contentHeight = Math.max(minHeight, editor.getContentHeight());
      dom.style.height = contentHeight + "px";
      editor.layout({
        width: container.offsetWidth,
        height: contentHeight,
      });
    });
  });
});
