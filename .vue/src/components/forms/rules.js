const required = v => !!v || "Requerido.";

const email = v =>
  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
    v
  ) || "E-mail inválido.";

const phone = v =>
  /\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}/.test(v) || "Telefone inválido.";

const lowerCase = v => /^[a-z]+$/.test(v) || "Apenas letras minúsculas.";

export { required, email, phone, lowerCase };
