def define_env(env):
    @env.macro
    def codebox(*, title, language, filepath):
        return f"""
```{language} title='{title}'
--8<-- "{filepath}"
```
"""
