function FeedbackSelector(form, field = '*', element = true) {
  return element
    ? (
        field == '*'
        ? form.find(`small[data-role="feedback"]`)
        : form.find(`small[data-role="feedback"][data-field="${field}"]`)
      )
    : `small[data-role="feedback"][data-field="${field}"]`;
}

function fbsel(form, field = '*', element = true) {
  return FeedbackSelector(form, field, element);
}

function ModalSelector(entity, method, element = true) {
  return element
    ? $(`.modal[data-entity="${entity}"][data-method="${method}"]`)
    : `.modal[data-entity="${entity}"][data-method="${method}"]`;
}

function modsel(entity, method, element = true) {
  return ModalSelector(entity, method, element);
}

function TableSelector(entity, element = true) {
  return element
    ? $(`table[data-entity="${entity}"]`)
    : `table[data-entity="${entity}"]`;
}

function tbsel(entity, element = true) {
  return TableSelector(entity, element);
}

function AccordionSelector(entity, element = true) {
  return element
    ? $(`div[data-role="accordion"][data-entity="${entity}"]`)
    : `div[data-role="accordion"][data-entity="${entity}"]`;
}

function accsel(entity, element = true) {
  return AccordionSelector(entity, element);
}

function TBodySelector(role, element = true) {
  return element
    ? $(`tbody[data-role="${role}"]`)
    : `tbody[data-role="${role}"]`;
}

function tbysel(role, element = true) {
  return TBodySelector(role, element);
}

function SearchSelector(element = true) {
  return element
    ? $(`form[data-role="search"]`)
    : `form[data-role="search"]`;
}

function scsel(element = true) {
  return SearchSelector(element);
}