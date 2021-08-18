const error = {
  id: "A propiedade options.id deve ser um número ou uma string.",
  prop: "A propiedade options.prop deve ser uma string.",
  data: "A propiedade options.data deve ser um objeto.",
  params: "A propiedade options.params deve ser um objeto.",
  file: "A propiedade options.params deve ser um objeto File.",
  onUploadProgress:
    "A propiedade options.onUploadProgress deve ser uma função.",
  save: "A propiedade options.save deve ser booleana ou uma função.",
  keepCache: "A propiedade options.save deve ser booleana.",
};

const validations = {
  id: (v) => typeof v === "string" || typeof v === "number",
  prop: (v) => typeof v === "string",
  data: (v) => typeof v === "object",
  params: (v) => typeof v === "object",
  file: (v) => v instanceof File,
  onUploadProgress: (v) => typeof v === "function",
  save: (v) => typeof v === "boolean" || typeof v === "function",
  keepCache: (v) => typeof v === "boolean",
};

const validateOptions = (options) => {
  if (typeof options !== "object") {
    throw new TypeError("O parâmetro <options> deve ser um objeto.");
  }

  for (let key in options) {
    if (!validations[key](options[key])) throw new TypeError(error[key]);
  }
};

export default validateOptions;
