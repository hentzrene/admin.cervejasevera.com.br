export default ({ value }) => {
  if (!value) return "";

  if (value == "0") {
    return "Não";
  } else {
    return "Sim";
  }
};
