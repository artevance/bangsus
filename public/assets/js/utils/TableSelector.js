function TableSelector(entity, element = true) {
  return element
    ? $(`table[data-entity="${entity}"]`)
    : `table[data-entity="${entity}"]`;
}

function tbsel(entity, element = true) {
  return TableSelector(entity, element);
}

function TBodySelector(role, element = true) {
  return element
    ? $(`tbody[data-role="${role}"]`)
    : `tbody[data-role="${role}"]`;
}

function tbysel(role, element = true) {
  return TBodySelector(role, element);
}