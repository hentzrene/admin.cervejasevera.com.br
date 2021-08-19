export default ({ value }) =>
  value &&
  new Date(value).toLocaleString("pt-BR", {
    timeStyle: "short",
  });
