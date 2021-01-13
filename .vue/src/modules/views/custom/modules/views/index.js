import CustomAdd from "./custom/Add";
import CustomEdit from "./custom/Edit";
import TableAdd from "./table/add/Index";
import TableEdit from "./table/edit/Index";

const add = {
  Custom: CustomAdd,
  Table: TableAdd
};

const edit = {
  Custom: CustomEdit,
  Table: TableEdit
};

export { add, edit };
