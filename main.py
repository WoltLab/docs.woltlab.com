def define_env(env):
    @env.macro
    def codebox(language, filepath, title = ""):
        if title is not "":
            return f"""
<div class="titledCodeBox">
    <div class="codeBoxTitle"><code>{title}</code></div>
    ```{language}
    --8<-- "{filepath}"
    ```
</div>
"""
        else:
            return f"""
```{language}
--8<-- "{filepath}"
```
"""
