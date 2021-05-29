// mixin
const breakpoint = {
  xl: "lgOnly",
  lg: "lgAndUp",
  md: "mdAndUp",
  sm: "smAndUp",
  xs: "xsOnly"
};

const regExp = new RegExp(`(${Object.keys(breakpoint).join("|")})$`, "g");

const mixin = {
  computed: {
    breakpoint() {
      const _breakpoint = { ...breakpoint };

      for (let bp in _breakpoint)
        _breakpoint[bp] = this.$vuetify.breakpoint[_breakpoint[bp]];

      return _breakpoint;
    },
    style() {
      const style = {};

      for (let prop in this.$props) {
        if (this[prop]) {
          const bp = prop.toLowerCase().match(regExp),
            mainProp = bp ? prop.slice(0, -2) : prop;

          if (!bp || this.breakpoint[bp[0]]) {
            style[this.propsCSS[mainProp]] = this[prop];
          }
        }
      }

      return style;
    }
  }
};

// mountProps
const mountProps = props => {
  const _props = {};

  for (let p of props) {
    _props[p] = String;
    for (let bp in breakpoint) _props[`${p}-${bp}`] = String;
  }

  return _props;
};

export { mixin, mountProps };
