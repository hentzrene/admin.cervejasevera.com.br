export default ({ value }) =>
  value &&
  new Date(value + "T12:00").toLocaleString("pt-BR", {
    dateStyle: "short",
  });
