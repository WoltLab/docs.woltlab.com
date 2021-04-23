def define_env(env):
    @env.macro
    def codebox(title = None, language = "", filepath = None, contents = ""):
        if title is not None:
            if filepath is not None:
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
<div class="titledCodeBox">
    <div class="codeBoxTitle"><code>{title}</code></div>
```{language}
{contents}
```
</div>
"""
        else:
            if filepath is not None:
                return f"""
```{language}
--8<-- "{filepath}"
```
"""
            else:
                return f"""
```{language}
{contents}
```
"""
