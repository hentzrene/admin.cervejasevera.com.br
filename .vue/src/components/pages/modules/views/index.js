import CustomAdd from "./custom/Add";
import CustomEdit from "./custom/Edit";
import TableAdd from "./table/add/Index";
import TableEdit from "./table/edit/Index";
import ItemAdd from "./item/add/Index";
import ItemEdit from "./item/edit/Index";

const add = {
  Custom: CustomAdd,
  Table: TableAdd,
  Item: ItemAdd
};

const edit = {
  Custom: CustomEdit,
  Table: TableEdit,
  Item: ItemEdit
};

export { add, edit };
