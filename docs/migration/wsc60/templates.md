# Migrating from WoltLab Suite 6.0 - Templates

## `unsafe` Prefix

The `unsafe` prefix is intended as a replacement for the `@` prefix in order to output the content of a variable unfiltered. The new prefix offers better readability and makes it easier to find places where the prefix is used unintentionally. The old `@` prefix is still supported, but we recommend using the new prefix for new code.

Usage:

```smarty
Old: {@$foo}
New: {unsafe:$foo}
```

The code listed above outputs the raw content of the variable `$foo`.

## Shared Templates

Shared templates, applicable both in the frontend and the backend, are now standardized to begin with the
prefix `shared_`.
This naming convention enhances clarity and organization within the template system. All templates intended for shared
use must adhere to this naming convention.

It is important to note that these shared templates must reside within the template directory in the frontend. Whether
they are located in the WCF template directory or in your own application's template directory.

To facilitate migration, below is a table outlining the renaming of templates from their old names to their
new `shared_` prefixed names:

| Old Template                         | New Template                              |
|--------------------------------------|-------------------------------------------|
| `__wysiwygPreviewFormButton`         | `shared_wysiwygPreviewFormButton`         |
| `__formButton`                       | `shared_formButton`                       |
| `__wysiwygSmileyFormContainer`       | `shared_wysiwygSmileyFormContainer`       |
| `__wysiwygTabMenuFormContainer`      | `shared_wysiwygTabMenuFormContainer`      |
| `__formContainer`                    | `shared_formContainer`                    |
| `__rowFormContainer`                 | `shared_rowFormContainer`                 |
| `__rowFormFieldContainer`            | `shared_rowFormFieldContainer`            |
| `__suffixFormFieldContainer`         | `shared_suffixFormFieldContainer`         |
| `__tabFormContainer`                 | `shared_tabFormContainer`                 |
| `__tabMenuFormContainer`             | `shared_tabMenuFormContainer`             |
| `__tabTabMenuFormContainer`          | `shared_tabTabMenuFormContainer`          |
| `__simpleAclFormField`               | `shared_simpleAclFormField`               |
| `__aclFormField`                     | `shared_aclFormField`                     |
| `__bbcodeAttributesFormField`        | `shared_bbcodeAttributesFormField`        |
| `__emptyFormFieldDependency`         | `shared_emptyFormFieldDependency`         |
| `__isNotClickedFormFieldDependency`  | `shared_isNotClickedFormFieldDependency`  |
| `__nonEmptyFormFieldDependency`      | `shared_nonEmptyFormFieldDependency`      |
| `__valueFormFieldDependency`         | `shared_valueFormFieldDependency`         |
| `__valueIntervalFormFieldDependency` | `shared_valueIntervalFormFieldDependency` |
| `__labelFormField`                   | `shared_labelFormField`                   |
| `__contentLanguageFormField`         | `shared_contentLanguageFormField`         |
| `__singleMediaSelectionFormField`    | `shared_singleMediaSelectionFormField`    |
| `__pollOptionsFormField`             | `shared_pollOptionsFormField`             |
| `__tagFormField`                     | `shared_tagFormField`                     |
| `__userFormField`                    | `shared_userFormField`                    |
| `__usernameFormField`                | `shared_usernameFormField`                |
| `__userPasswordFormField`            | `shared_userPasswordFormField`            |
| `__formFieldError`                   | `shared_formFieldError`                   |
| `__wysiwygAttachmentFormField`       | `shared_wysiwygAttachmentFormField`       |
| `__wysiwygFormField`                 | `shared_wysiwygFormField`                 |
| `__numericFormField`                 | `shared_numericFormField`                 |
| `__booleanFormField`                 | `shared_booleanFormField`                 |
| `__buttonFormField`                  | `shared_buttonFormField`                  |
| `__captchaFormField`                 | `shared_captchaFormField`                 |
| `__checkboxFormField`                | `shared_checkboxFormField`                |
| `__colorFormField`                   | `shared_colorFormField`                   |
| `__dateFormField`                    | `shared_dateFormField`                    |
| `__emailFormField`                   | `shared_emailFormField`                   |
| `__hiddenFormField`                  | `shared_hiddenFormField`                  |
| `__iconFormField`                    | `shared_iconFormField`                    |
| `__itemListFormField`                | `shared_itemListFormField`                |
| `__multilineTextFormField`           | `shared_multilineTextFormField`           |
| `__multipleSelectionFormField`       | `shared_multipleSelectionFormField`       |
| `__passwordFormField`                | `shared_passwordFormField`                |
| `__radioButtonFormField`             | `shared_radioButtonFormField`             |
| `__ratingFormField`                  | `shared_ratingFormField`                  |
| `__selectFormField`                  | `shared_selectFormField`                  |
| `__sourceCodeFormField`              | `shared_sourceCodeFormField`              |
| `__uploadFormField`                  | `shared_uploadFormField`                  |
| `__wysiwygSmileyFormNode`            | `shared_wysiwygSmileyFormNode`            |
| `__form`                             | `shared_form`                             |
| `__formContainerChildren`            | `shared_formContainerChildren`            |
| `__formContainerDependencies`        | `shared_formContainerDependencies`        |
| `__formField`                        | `shared_formField`                        |
| `__formFieldDependencies`            | `shared_formFieldDependencies`            |
| `__formFieldDescription`             | `shared_formFieldDescription`             |
| `__formFieldErrors`                  | `shared_formFieldErrors`                  |
| `__formFieldDataHandler`             | `shared_formFieldDataHandler`             |
| `__singleSelectionFormField`         | `shared_singleSelectionFormField`         |
| `__mediaSetCategoryDialog`           | `shared_mediaSetCategoryDialog`           |
| `__messageQuoteManager`              | `shared_messageQuoteManager`              |
| `__topReaction`                      | `shared_topReaction`                      |
| `__wysiwygCmsToolbar`                | `shared_wysiwygCmsToolbar`                |
| `aclPermissionJavaScript`            | `shared_aclPermissionJavaScript`          |
| `aclSimple`                          | `shared_aclSimple`                        |
| `articleAddDialog`                   | `shared_articleAddDialog`                 |
| `benchmark`                          | `shared_benchmark`                        |
| `booleanOptionType`                  | `shared_booleanOptionType`                |
| `booleanSearchableOptionType`        | `shared_booleanSearchableOptionType`      |
| `captcha`                            | `shared_captcha`                          |
| `categoryOptionList`                 | `shared_categoryOptionList`               |
| `checkboxesOptionType`               | `shared_checkboxesOptionType`             |
| `checkboxesSearchableOptionType`     | `shared_checkboxesSearchableOptionType`   |
| `codeMetaCode`                       | `shared_codeMetaCode`                     |
| `codemirror`                         | `shared_codemirror`                       |
| `colorPickerJavaScript`              | `shared_colorPickerJavaScript`            |
| `fontAwesomeJavaScript`              | `shared_fontAwesomeJavaScript`            |
| `formError`                          | `shared_formError`                        |
| `formNotice`                         | `shared_formNotice`                       |
| `formSuccess`                        | `shared_formSuccess`                      |
| `languageChooser`                    | `shared_languageChooser`                  |
| `lineBreakSeparatedTextOptionType`   | `shared_lineBreakSeparatedTextOptionType` |
| `mediaManager`                       | `shared_mediaManager`                     |
| `messageFormAttachments`             | `shared_messageFormAttachments`           |
| `messageTableOfContents`             | `shared_messageTableOfContents`           |
| `multipleLanguageInputJavascript`    | `shared_multipleLanguageInputJavascript`  |
| `passwordStrengthLanguage`           | `shared_passwordStrengthLanguage`         |
| `quoteMetaCode`                      | `shared_quoteMetaCode`                    |
| `radioButtonSearchableOptionType`    | `shared_radioButtonSearchableOptionType`  |
| `recaptcha`                          | `shared_recaptcha`                        |
| `scrollablePageCheckboxList`         | `shared_scrollablePageCheckboxList`       |
| `sitemapEnd`                         | `shared_sitemapEnd`                       |
| `sitemapEntry`                       | `shared_sitemapEntry`                     |
| `sitemapIndex`                       | `shared_sitemapIndex`                     |
| `sitemapStart`                       | `shared_sitemapStart`                     |
| `trophyImage`                        | `shared_trophyImage`                      |
| `unfurlUrl`                          | `shared_unfurlUrl`                        |
| `uploadFieldComponent`               | `shared_uploadFieldComponent`             |
| `userBBCodeTag`                      | `shared_bbcode_user`                      |
| `userConditions`                     | `shared_userConditions`                   |
| `userOptionsCondition`               | `shared_userOptionsCondition`             |
| `worker`                             | `shared_worker`                           |
| `wysiwyg`                            | `shared_wysiwyg`                          |
| `groupBBCodeTag`                     | `shared_bbcode_group`                     |
| `__videoAttachmentBBCode`            | `shared_bbcode_attach_video`              |
| `__audioAttachmentBBCode`            | `shared_bbcode_attach_audio`              |
| `mediaBBCodeTag`                     | `shared_bbcode_wsm`                       |
| `articleBBCodeTag`                   | `shared_bbcode_wsa`                       |
| `__multiPageCondition`               | `shared_multiPageCondition`               |
| `__multilineItemListFormField`       | `shared_multilineItemListFormField`       |

#### Migration

We provide a helper script that automates the task of updating the template includes. The script will search
for `{include file='old_template_name'}` and ensure that any content before or after
the `file='old_template_name'` remains available within the `{include}` tag.

The helper script is part of WoltLab Suite Core and can be found in the repository
at `extra/migrate-shared-template.php`. The script must be executed from CLI and requires PHP 8.1.

```shell
$> php extra/migrate-shared-template.php /path/to/the/target/directory/
```

The script will recursively search the specified target directory for files with the `tpl` extension.
