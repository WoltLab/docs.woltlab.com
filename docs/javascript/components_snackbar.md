# Snackbar

Snackbars show short notifications about user interactions at the bottom of the screen.

## Success Snackbar

Success snackbars indicate that an action initiated by the user has been successfully completed.
The message disappears automatically after 3 seconds.

### Examples

Display the success snackbar with the standard message:

```ts
import { showDefaultSuccessSnackbar } from "WoltLabSuite/Core/Component/Snackbar";

showDefaultSuccessSnackbar();
```

Display the success snackbar with your a custom message:

```ts
import { showSuccessSnackbar } from "WoltLabSuite/Core/Component/Snackbar";
import { getPhrase } from "WoltLabSuite/Core/Language";

showSuccessSnackbar(getPhrase('custom.success.message'));
```

Use an event to perform an action after the snackbar has been closed:

```ts
import { showDefaultSuccessSnackbar } from "WoltLabSuite/Core/Component/Snackbar";

showDefaultSuccessSnackbar().addEventListener("snackbar:close", () => {
  // perform an action
});
```

## Progress Snackbar

Progress snackbars show the progress of an action that was initiated by the user and is executed in several individual steps.
Once the action has been completed, a progress snackbar automatically becomes a success snackbar.

### Examples

```ts
import { showProgressSnackbar } from "WoltLabSuite/Core/Component/Snackbar";

const snackbar = showProgressSnackbar('label', 10);

for (let i = 0; i < 10; i++) {
  // perform one step of an action
  
  snackbar.setIteration(i + 1);
}

snackbar.markAsDone();
```
