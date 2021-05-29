const required = v => !!v || "Requerido.";

const email = v =>
  !v ||
  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
    v
  ) ||
  "E-mail inválido.";

const phone = v =>
  !v || /\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}/.test(v) || "Telefone inválido.";

const alphaNumUnderline = v =>
  !v ||
  /^[a-z0-9_]+$/.test(v) ||
  "Apenas caracteres alfanuméricos minúsculos e underline.";

const url = v =>
  !v ||
  /^(https?:\/\/)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\\+.~#?&//=]*)$/.test(
    v
  ) ||
  "URL inválida.";

const price = v =>
  [
    /^[0-9]*$/,
    /^[0-9]+(,|.)$/,
    /^[0-9]+(,|.)[0-9]{1}$/,
    /^[0-9]+(,|.)[0-9]{2}$/
  ].find(p => p.test(v))
    ? true
    : "Preço inválido.";

export { required, email, phone, alphaNumUnderline, url, price };
