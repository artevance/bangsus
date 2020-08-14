function ModalSelector(entity, method, element = true) {
  return element
    ? $(`.modal[data-entity="${entity}"][data-method="${method}"]`)
    : `.modal[data-entity="${entity}"][data-method="${method}"]`;
}

function modsel(entity, method, element = true) {
  return ModalSelector(entity, method, element);
}