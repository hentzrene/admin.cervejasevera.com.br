const mixin = {
  computed: {
    style() {
      const style = {},
        regExp = /(Sm|Md|Xl|Lg)$/;

      for (let prop in this.$options.propsData) {
        const bp = prop.match(regExp),
          mainProp = bp ? prop.slice(0, -2) : prop;

        style["--grid-" + prop.replace(/([A-Z])/g, "-$1").toLowerCase()] = this
          .propsCSS[mainProp].parse
          ? this.propsCSS[mainProp].parse(this[prop])
          : this[prop];
      }

      return style;
    },
    classes() {
      const classes = [];

      for (let prop in this.$options.propsData) {
        classes.push("grid-" + prop.replace(/([A-Z])/g, "-$1").toLowerCase());
      }

      return classes;
    },
  },
};

export default mixin;
