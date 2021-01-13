import { dateDiff } from "./utils";

/**
 * Transformar str em sigla.
 *
 * @param {string} str
 */
const toInitials = str => str.replace(/([^\s])[^\s]+\s?/g, "$1.");

/**
 * Reduzir tamanho de str para menor que limit não quebrando as palavras e depois
 * adicionando reticências no final.
 *
 * @param {string} str
 * @param {number} limit
 */
const limitString = (str, limit) => {
  if (str.length <= limit) return str;

  const ws = str.split(" ");
  let r = "";

  if (ws.length === 1) return str.slice(0, limit) + "...";

  for (let w of ws) {
    if (r.length + w.length <= limit) r += " " + w;
    else break;
  }

  return r + "...";
};

/**
 * Verificar se date é um objeto `Date` ou se possível criar um usando date como parâmetro.
 * @param {string | Date} date
 */
const verifyDate = date => {
  if (typeof date === "string") return new Date(date);
  else if (date instanceof Date) return date;
  else throw new Error(`${date} não é um objeto data ou uma string.`);
};

const RTF = new Intl.RelativeTimeFormat("pt-BR", { numeric: "auto" });
/**
 * Transformar a data d no formato local.
 * @param {string | date} d
 */
const toLocaleDate = d => {
  d = verifyDate(d);
  const hj = new Date();
  let diff = dateDiff(hj, d, "days");

  if (Math.abs(diff) > 30) {
    return RTF.format(diff, "month");
  } else if (Math.abs(diff) > 0) {
    return RTF.format(diff, "day");
  }

  diff = dateDiff(hj, d, "hours");
  if (Math.abs(diff) > 1) return RTF.format(diff, "hour");

  diff = dateDiff(hj, d, "minutes");
  if (Math.abs(diff) > 1) return RTF.format(diff, "minute");

  diff = dateDiff(hj, d, "seconds");
  if (Math.abs(diff) > 0) return RTF.format(diff, "second");

  return "agora";
};

/**
 * Transformar a data d no formato de date para HTML.
 * @param {string | date} d
 */
const toDateHTML = d =>
  verifyDate(d)
    .toLocaleDateString()
    .replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$2-$1");

/**
 * Transformar a data d no formato de datetime para HTML.
 * @param {string | date} d
 */
const toDateTimeHTML = d =>
  verifyDate(d)
    .toLocaleString()
    .replace(/(\d{2})\/(\d{2})\/(\d{4}) (.+)/, "$3-$2-$1T$4")
    .slice(0, -3);

/**
 * Transformar a data d no formato de datetime para MySQL.
 * @param {string | date} d
 */
const toDateMysql = d =>
  verifyDate(d)
    .toLocaleString()
    .replace(/(\d{2})\/(\d{2})\/(\d{4}) (.+)/, "$3-$2-$1 $4")
    .slice(0, -3);

/**
 * Transformar a data d no formato de datetime para MySQL.
 * @param {string | date} d
 */
const toTime = d =>
  verifyDate(d).toLocaleTimeString("pt-BR", { timeStyle: "short" });

/**
 * Reduzir nome para apenas primeiro nome e sobrenome.
 * @param {string} v
 */
const simplifyName = v => v.replace(/([^\s])\s.+\s([^\s])/, "$1 $2");

export {
  toInitials,
  limitString,
  toLocaleDate,
  toDateHTML,
  toDateTimeHTML,
  toDateMysql,
  toTime,
  simplifyName
};
