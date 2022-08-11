const camelCaseToHyphen = (str) =>
  str
    .replace(/[A-Z]/g, (match) => "-" + match.toLocaleLowerCase())
    .replace(/_/g, "-");

export default camelCaseToHyphen;
