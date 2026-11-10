# Migrating from WoltLab Suite 6.2 - Templates

## Form Builder Markup

The markup of [form builder](../../php/api/form_builder/overview.md) forms no longer uses `dl`, `dt`, and `dd` elements.
Form fields are rendered in a `div.formField` element instead, whose label, errors, and description are linked to the field via ARIA attributes.
Forms that are not created with the form builder are not affected by this change.

The id `{prefixedId}Container` of the field container, the `formError` class of fields with errors, and the inline `display: none` of fields with unmet dependencies are unchanged.

#### Previous Code Example

```smarty
<dl id="{$field->getPrefixedId()}Container">
	<dt><label for="{$field->getPrefixedId()}">{unsafe:$field->getLabel()}</label> <span class="formFieldRequired">*</span></dt>
	<dd>
		{unsafe:$field->getFieldHtml()}

		<small class="innerError">…</small>
		<small>{unsafe:$field->getDescription()}</small>
	</dd>
</dl>
```

#### New Code Example

```smarty
<div id="{$field->getPrefixedId()}Container" class="formField">
	<div class="formField__label">
		<label id="{$field->getPrefixedId()}Label" for="{$field->getLabelTarget()}">{unsafe:$field->getLabel()}</label>
		<span class="formField__required" aria-hidden="true">*</span>
	</div>
	<div class="formField__input">
		{unsafe:$field->getFieldHtml()}
	</div>

	<div id="{$field->getPrefixedId()}Error" class="formField__errors">
		<p class="formField__error">…</p>
	</div>
	<div id="{$field->getPrefixedId()}Description" class="formField__description">{unsafe:$field->getDescription()}</div>
</div>
```

The errors and the description are placed after `div.formField__input`, not inside it.
The `small` elements and the classes `innerError` and `formFieldRequired` are no longer used by form builder forms.

### Grouped Fields

Form fields that consist of multiple elements, like radio buttons or a range with two inputs, are rendered as a group.
The field container has `role="group"` and is labelled by the label of the field, which is rendered as a `span` element instead of a `label` element:

```smarty
<div id="{$field->getPrefixedId()}Container" class="formField" role="group" aria-labelledby="{$field->getPrefixedId()}Label">
	<div class="formField__label">
		<span id="{$field->getPrefixedId()}Label">{unsafe:$field->getLabel()}</span>
	</div>
	…
</div>
```

See [`getLabelTarget()`](php.md#form-builder) for how a form field marks itself as a group.

### Inline Labels

Fields whose label is shown next to the input, like checkboxes, use the additional class `formField--inlineLabel` for the field container.
The input and its label text are wrapped in `label.formField__inlineLabel`, and the label text in `span.formField__inlineLabelText`.
The options of `RadioButtonFormField` use `label.formField__inlineLabel` as well.

### Containers

`RowFormFieldContainer` and `SuffixFormFieldContainer` use the same `div.formField` structure.
The children of a `RowFormFieldContainer` are wrapped in `div.formField__gridItem` elements, which contain a visually hidden label and the description of the respective child.

`FormContainer` uses the CSS class `formSection` instead of `section`.
Its header uses the following classes:

```smarty
<section id="{$container->getPrefixedId()}Container" class="formSection" aria-labelledby="{$container->getPrefixedId()}Title">
	<header class="formSection__header">
		<h2 id="{$container->getPrefixedId()}Title" class="formSection__title">… <span class="formSection__required">*</span></h2>
		<p class="formSection__description">…</p>
	</header>
	…
</section>
```

Code that removes the `section` class from a container, like `->removeClass('section')`, has to remove the `formSection` class instead.

### Form and Submit Buttons

The form element (or the `section` element of forms in dialogs) has the CSS class `formBuilder`.
The submit buttons of the form are wrapped in `div.formBuilder__submit` instead of `div.formSubmit`.
JavaScript code that looks for the submit buttons of form builder forms has to match `.formBuilder__submit` in addition to `.formSubmit`.

### Custom Form Field Templates

Templates of custom form fields have to be adjusted if they render the surrounding field structure themselves instead of only the field HTML:

- Replace the `dl`, `dt`, and `dd` elements with the `div.formField` structure shown above.
- Include `shared_formFieldErrors` and `shared_formFieldDescription` after `div.formField__input`.
- Give the `label` element the id `{prefixedId}Label` so that client-side errors can find the labelled element.

Templates that only render the field HTML have to output the ARIA attributes of the field on the labelled element.
Fields implementing `IAttributeFormField` that output `getFieldAttributes()` on the labelled element already include them, other fields have to output `getAriaAttributes()`:

```smarty
<select id="{$field->getPrefixedId()}" name="{$field->getPrefixedId()}"{*
	*}{foreach from=$field->getAriaAttributes() key='attributeName' item='attributeValue'} {$attributeName}="{$attributeValue}"{/foreach}>
	…
</select>
```

Client-side errors of form builder fields have to be shown with `setFieldError()` instead of `DomUtil.innerError()`, see [form validation](../../php/api/form_builder/validation_data.md#iformfieldvalidationerror-formfieldvalidationerror).

## Label Picker

Labels are selected through the form-associated element `<woltlab-core-label-picker>`, which replaces `WCF.Label.Chooser` and `WoltLabSuite/Core/Form/Builder/Field/Controller/Label`.
`ViewableLabelGroup::renderPicker()` renders the element for a label group:

```smarty
{assign var='labelPickerName' value='labelIDs['|concat:$labelGroup->groupID:']'}
{if $labelIDs[$labelGroup->groupID]|isset}
	{unsafe:$labelGroup->renderPicker($labelPickerName, $labelIDs[$labelGroup->groupID])}
{else}
	{unsafe:$labelGroup->renderPicker($labelPickerName)}
{/if}
```

The parameters of `renderPicker()` are the name of the field, the selected value, whether a label is required, whether the value `-1` for objects without any label of the group can be selected, the id of the element, and the maximum number of labels that can be selected.

The element submits the id of the selected label, `0` for no selection or `-1` for objects without any label of the group.
If the attribute `max` is greater than `1`, each selected label is submitted as `name[]` instead.
The properties `value` and `values` of the element return the selected label, or the selected labels as a list, and the element dispatches `input` and `change` events when the selection changes.

## Labels

Labels are rendered with the CSS class `labelBadge` instead of `badge label`, so that they are styled independently of status badges.
`Label::render()` already outputs the new markup, templates that render labels themselves have to be adjusted:

```smarty
<span class="labelBadge{if $label->getClassNames()} {$label->getClassNames()}{/if}">{$label->getTitle()}</span>
```

The colors of a label are defined by the CSS custom properties `--label-background` and `--label-color`.
Custom CSS classes of labels should set these properties instead of `background-color` and `color`:

```scss
.labelBadge.myCustomLabel {
	--label-background: #ffe0b2;
	--label-color: #6d3200;
}
```

In addition to the existing colors, labels can use the colors `teal`, `lime`, `navy`, and `maroon`.
These colors are not available for badges such as user ranks.

### Label Lists

Lists of labels are rendered in a `div` element with the CSS class `labelBadgeList` or `labelBadgeInlineList` instead of a `ul.labelList` element.
The labels are direct children of the container and are no longer wrapped in `li` elements.
`labelBadgeList` places the labels on a separate line, `labelBadgeInlineList` places them inline with the surrounding content, for example next to the tag icon in the content header.

The CSS class `labelList` is deprecated.

#### Previous Code Example

```smarty
<ul class="labelList">
	{foreach from=$object->getLabels() item=label}
		<li>{unsafe:$label->render()}</li>
	{/foreach}
</ul>
```

#### New Code Example

```smarty
<div class="labelBadgeList">
	{foreach from=$object->getLabels() item=label}
		{unsafe:$label->render()}
	{/foreach}
</div>
```

Template listeners for the template events `beforeLabels` and `afterLabels` of the templates `threadList` and `threadContentHeaderTitle` have to output the labels without a surrounding `li` element.

## Form Styles

The styling of inputs, labels, and required marks has changed for all forms, including forms not created with the form builder:

- Inputs use a padding of `8px 12px` and show a focus ring when focused with the keyboard.
- Checkboxes and radio buttons use `--wcfButtonPrimaryBackground` as their accent color.
- The default values of the style variable `wcfInputLabel` match `wcfContentText`, and labels are shown with a font weight of 500.
- Required marks are shown in `--wcfContentDimmedText` instead of red.
- Labels of fields with errors are no longer colored red.
