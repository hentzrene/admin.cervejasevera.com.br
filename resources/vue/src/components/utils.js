const getTelLinkFromPhone = (phone) => "tel:+55" + phone.replace(/[^0-9]/g, "");

const getWhatsappLinkFromPhone = (phone) =>
  "https://wa.me/55" + phone.replace(/[^0-9]/g, "");

/**
 * Obter código do vído do youtube por sua URL.
 *
 * @param {string} url
 */
const getYoutubeCode = (url) => {
  url = new URL(url);

  let code;
  switch (url.host) {
    case "www.youtube.com":
      code = url.searchParams.get("v");
      break;
    case "youtu.be":
      code = url.pathname.replace(/\/(.+)\/?.*/, "$1");
      break;
  }
  return code;
};

const toFriendlyURL = (str) =>
  str
    .normalize("NFKD")
    .replace(/\s/g, "-")
    .replace(/([^\u0030-\u0039\u0041-\u005A^\u0061-\u007A-])/g, "")
    .toLocaleLowerCase();

const _MS_PER_MIN = 1000 * 60;
const _MS_PER_HOUR = _MS_PER_MIN * 60;
const _MS_PER_DAY = _MS_PER_HOUR * 24;
const _UTC_DAY = ["getFullYear", "getMonth", "getDate"];
const _UTC_HOUR = [..._UTC_DAY, "getHours"];
const _UTC_MIN = [..._UTC_HOUR, "getMinutes"];
const _UTC_SEC = [..._UTC_MIN, "getSeconds"];
const _utcSec = (d) => Date.UTC(..._UTC_SEC.map((f) => d[f]()));
const _utcMin = (d) => Date.UTC(..._UTC_MIN.map((f) => d[f]()));
const _utcHour = (d) => Date.UTC(..._UTC_HOUR.map((f) => d[f]()));
const _utcDay = (d) => Date.UTC(..._UTC_DAY.map((f) => d[f]()));

/**
 * Obter a diferença entre duas data de acordo com o parâmetro type.
 *
 * @param {Date} d1 Subtraendo
 * @param {Date} d2 Minuendo
 * @param {string} type minutes | hours | days
 */
const dateDiff = (d1, d2, type = "days") => {
  let div, utc1, utc2;

  switch (type) {
    case "seconds":
      utc1 = _utcSec(d1);
      utc2 = _utcSec(d2);
      div = 1000;
      break;
    case "minutes":
      utc1 = _utcMin(d1);
      utc2 = _utcMin(d2);
      div = _MS_PER_MIN;
      break;
    case "hours":
      utc1 = _utcHour(d1);
      utc2 = _utcHour(d2);
      div = _MS_PER_HOUR;
      break;
    case "days":
      utc1 = _utcDay(d1);
      utc2 = _utcDay(d2);
      div = _MS_PER_DAY;
      break;
  }

  return (utc2 - utc1) / div;
};

const formatToURL = (str) =>
  str
    .normalize("NFD")
    .replace(/\s+/g, "-")
    .replace(/([\u0300-\u036f]|[^0-9a-zA-Z-])/g, "")
    .toLocaleLowerCase();

const groupBy = (arr, key) =>
  arr.reduce((rv, x) => {
    (rv[x[key]] = rv[x[key]] || []).push(x);
    return rv;
  }, {});

export {
  getTelLinkFromPhone,
  getWhatsappLinkFromPhone,
  getYoutubeCode,
  toFriendlyURL,
  dateDiff,
  formatToURL,
  groupBy,
};
