import bigText from "./components/big-text";
import category from "./components/category";
import collection from "./components/collection";
import collectionWithKey from "./components/collection-with-key";
import color from "./components/color";
import date from "./components/date";
import datetime from "./components/datetime";
import email from "./components/email";
import file from "./components/file";
import hidden from "./components/hidden";
import imageFile from "./components/image-file";
import link from "./components/link";
import mediumText from "./components/medium-text";
import number from "./components/number";
import password from "./components/password";
import price from "./components/price";
import readOnly from "./components/read-only";
import sourceCode from "./components/source-code";
import subcategory from "./components/subcategory";
import switchBoolean from "./components/switch-boolean";
import tags from "./components/tags";
import time from "./components/time";
import tinyText from "./components/tiny-text";
import url from "./components/url";

const fields = {
  bigText,
  category,
  collection,
  collectionWithKey,
  color,
  date,
  datetime,
  email,
  file,
  hidden,
  imageFile,
  link,
  mediumText,
  number,
  password,
  price,
  readOnly,
  sourceCode,
  subcategory,
  switchBoolean,
  tags,
  time,
  tinyText,
  url,
};

const fieldsEntries = Object.entries(fields);

export const fieldsFormatForDisplay = Object.fromEntries(
  fieldsEntries
    .filter(([, val]) => !!val.formatForDisplay)
    .map(([key, val]) => [key, val.formatForDisplay])
);

export const fieldsHeaderFilterComponents = Object.fromEntries(
  fieldsEntries
    .filter(([, val]) => !!val.headerFilterComponent)
    .map(([key, val]) => [key, val.headerFilterComponent])
);

export default Object.fromEntries(
  fieldsEntries.map(([, val]) => [val.name, val.component])
);
