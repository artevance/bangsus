function FeedbackSelector(form, field, element = true) {
  console.log(form.find(`small[data-role="feedback"][data-field="${field}"]`));
  return element
    ? form.find(`small[data-role="feedback"][data-field="${field}"]`)
    : `small[data-role="feedback"][data-field="${field}"]`;
}

function fbsel(form, field, element = true) {
  return FeedbackSelector(form, field, element);
}