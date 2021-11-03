const breakpoints = ["sm", "md", "xl", "lg"];

const mountProps = (props) => {
  const _props = {};

  for (let p in props) {
    _props[p] = { type: props[p].type, default: props[p].default };
    for (let bp of breakpoints)
      _props[`${p}-${bp}`] = {
        type: props[p].type,
        default: props[p].default,
      };
  }

  return _props;
};

export default mountProps;
