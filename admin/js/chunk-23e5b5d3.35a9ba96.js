(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-23e5b5d3"],{"0cfd":function(e,t,s){},"25f5":function(e,t,s){"use strict";s("0cfd")},"608c":function(e,t,s){},"6f7d":function(e,t,s){},"8a75":function(e,t,s){"use strict";s("6f7d")},"8f5a":function(e,t,s){},"9b01":function(e,t,s){"use strict";s("9e11")},"9e11":function(e,t,s){},a968:function(e,t,s){"use strict";s.r(t);var i=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("div",[s("div",{staticClass:"d-flex align-center mb-2"},[s("div",{staticClass:"white--text font-weight-bold text-body-1"},[e._v("Campos")]),s("tooltip",{attrs:{tip:"Adicionar",top:""}},[s("v-btn",{staticClass:"ml-2",attrs:{color:"secondary",fab:"",depressed:"","x-small":""},on:{click:function(t){e.addDialog=!0}}},[s("v-icon",[e._v("fas fa-plus")])],1)],1)],1),e.data.fields&&e.data.fields.length?s("field-list",{ref:"fieldList",attrs:{items:e.data.fields},on:{changefieldkey:e.updateListHeaders}}):s("div",{staticClass:"pa-4 text-body-2 text-center font-weight-bold"},[e._v("Nenhum campo foi adicionado!")])],1),s("div",{staticClass:"mt-6 mb-4"},[e._m(0),s("v-chip-group",{attrs:{column:"",multiple:""},model:{value:e.listHeaders,callback:function(t){e.listHeaders=t},expression:"listHeaders"}},[e._l(e.data.fields,(function(t,i){var a=t.name,r=t.key;return s("v-chip",{key:i,attrs:{value:r,filter:"",outlined:""}},[e._v(e._s(a))])})),s("v-btn",{attrs:{loading:e.sendingListHeaders,color:"secondary darken-1",fab:"",small:"",depressed:""},on:{click:e.sendListHeaders}},[s("v-icon",[e._v("fas fa-save")])],1)],2),e.noListHeaders?s("div",{staticClass:"pt-8 text-body-2 text-center font-weight-bold red--text"},[e._v("Deve haver no mínimo um cabeçalho.")]):e._e(),s("add-field",{attrs:{types:e.fieldsTypes},model:{value:e.addDialog,callback:function(t){e.addDialog=t},expression:"addDialog"}})],1)])},a=[function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"d-flex align-center mb-2"},[s("div",{staticClass:"white--text font-weight-bold text-body-1"},[e._v("Cabeçalhos da lista")])])}],r=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("v-card",{staticClass:"pa-3",attrs:{color:"primary","max-width":"400"}},[s("v-form",{ref:"form",staticClass:"field-item"},[s("div",{staticClass:"field-item-inputs d-flex align-center"},[s("v-text-field",{attrs:{value:e.data.name,loading:e.updating.name,disabled:e.updating.name||e.updating.typeId||e.removing,label:"Nome",dense:"",solo:"","hide-details":""},on:{keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.updateProperty(t.target.value,"name")},blur:function(t){return e.updateProperty(t.target.value,"name")}}}),s("v-select",{attrs:{value:e.data.typeId,items:e.types,loading:e.updating.typeId,disabled:e.updating.name||e.updating.typeId||e.removing,label:"Tipo",dense:"",solo:"","hide-details":""},on:{input:function(t){return e.updateProperty(t,"typeId")}}}),s("v-btn",{attrs:{loading:e.removing,color:"secondary darken-2",small:"",icon:""},on:{click:e.remove}},[s("v-icon",{attrs:{small:""}},[e._v("fas fa-trash")])],1)],1)])],1)},n=[],l=s("917e"),o=s("5f42"),d={props:{data:{type:Object,default:()=>({id:null,name:null,key:null,type:null,required:!1,unique:!1,private:!1})},types:{type:Array,required:!0}},data:()=>({rules:{required:o["c"],lowerCase:o["b"]},removing:!1,updating:{name:!1,typeId:!1}}),computed:{moduleId(){return this.$route.params.module}},methods:{validate(){return this.$refs.form.validate()},remove(){this.removing=!0,this.$rest("modulesFields").delete({id:this.data.id,params:{moduleId:this.moduleId}}).then(()=>this.$emit("remove")).finally(()=>this.removing=!1)},updateProperty(e,t){if(this.data[t]===e)return!1;this.updating[t]=!0,this.$rest("modulesFields").put({id:this.data.id,data:{value:e},prop:t,params:{moduleId:this.moduleId}}).then(()=>this.data[t]=e).finally(()=>this.updating[t]=!1)}},components:{Tooltip:l["a"]}},c=d,h=(s("9b01"),s("2877")),u=s("6544"),p=s.n(u),f=s("8336"),v=s("b0af"),m=s("4bd4"),g=s("132d"),y=s("b974"),w=s("8654"),x=Object(h["a"])(c,r,n,!1,null,null,null),b=x.exports;p()(x,{VBtn:f["a"],VCard:v["a"],VForm:m["a"],VIcon:g["a"],VSelect:y["a"],VTextField:w["a"]});var $=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("div",{ref:"fieldList",staticClass:"field-list d-flex flex-wrap"},e._l(e.items,(function(t,i){return s("field-item",{key:i,attrs:{types:e.fieldsTypes,data:t},on:{remove:function(t){return e.removeField(i)},changekey:function(t){return e.$emit("changefieldkey")}}})})),1),e.repeteadKey?s("div",{staticClass:"pt-8 text-body-2 text-center font-weight-bold red--text"},[e._v("Não pode existir alguma chave de campo repetida.")]):e._e()])},C=[],O={props:{items:{type:Array,default:()=>[]}},data:()=>({selected:null,repeteadKey:!1}),computed:{fieldsTypes(){return this.$rest("modulesFieldsTypes").list.map(({id:e,name:t})=>({text:t,value:e}))}},methods:{removeField(e){this.items.splice(e,1)},validate(){const e=[];if(this.$children.forEach(t=>e.push(t.validate())),e.includes(!1))return!1;const t=[];this.repeteadKey=!1;for(const{key:s}of this.items){if(t.includes(s))return this.repeteadKey=!0,!1;t.push(s)}return!0}},beforeCreate(){this.$rest("modulesFieldsTypes").get()},components:{FieldItem:b}},k=O,_=(s("25f5"),Object(h["a"])(k,$,C,!1,null,null,null)),I=_.exports,T=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("v-dialog",{attrs:{value:e.value,"max-width":"340px"},on:{input:function(t){return e.$emit("input",t)}}},[s("v-card",{staticClass:"pa-4",attrs:{dark:""}},[s("div",{staticClass:"title text-center mb-4"},[e._v("Adicionar campo")]),s("v-form",{ref:"form"},[s("v-row",[s("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[s("v-text-field",{attrs:{rules:[e.rules.required],label:"Nome",dense:"",outlined:""},model:{value:e.name,callback:function(t){e.name=t},expression:"name"}})],1),s("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[s("v-text-field",{attrs:{rules:[e.rules.required,e.rules.lowerCase],label:"Chave",dense:"",outlined:""},on:{input:function(t){return e.$emit("changekey")}},model:{value:e.key,callback:function(t){e.key=t},expression:"key"}})],1),s("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[s("v-select",{attrs:{items:e.types,rules:[e.rules.required],label:"Tipo",dense:"",outlined:""},model:{value:e.type,callback:function(t){e.type=t},expression:"type"}})],1),s("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[s("div",{staticClass:"field-item-switches d-flex"},[s("tooltip",{attrs:{tip:"Único",top:""}},[s("v-btn",{attrs:{icon:""},on:{click:function(t){e.unique=!e.unique}}},[s("v-icon",{attrs:{color:"cyan",disabled:!e.unique}},[e._v("fas fa-fingerprint")])],1)],1),s("tooltip",{attrs:{tip:"Privado",top:""}},[s("v-btn",{attrs:{icon:""},on:{click:function(t){e.private=!e.private}}},[s("v-icon",{attrs:{color:"green",disabled:!e.private}},[e._v("fas fa-shield-alt")])],1)],1)],1)]),s("v-col",{staticClass:"py-0 mt-2 mt-sm-0",attrs:{cols:"12"}},[s("v-btn",{attrs:{loading:e.sending,color:"secondary darken-2",depressed:"",block:""},on:{click:e.send}},[s("span",{staticClass:"text-none"},[e._v("Adicionar")])])],1)],1)],1)],1)],1)},H=[],L={props:{value:{type:Boolean,default:!1},types:{type:Array,required:!0}},data:()=>({rules:{required:o["c"],lowerCase:o["b"]},name:null,key:null,type:null,unique:!1,private:!1,sending:!1}),computed:{moduleId(){return this.$route.params.module}},methods:{send(){const e=this.$refs.form;e.validate()&&(this.sending=!0,this.$rest("modulesFields").post({data:{name:this.name,key:this.key,type:this.type,unique:this.unique,private:this.private},params:{moduleId:this.moduleId}}).then(()=>this.$rest("modules").get({id:this.moduleId}).then(()=>{this.$emit("input",!1),this.sending=!1})).catch(()=>this.sending=!1))}},components:{Tooltip:l["a"]}},V=L,A=(s("8a75"),s("62ad")),q=s("169a"),F=s("0fd9"),M=Object(h["a"])(V,T,H,!1,null,null,null),P=M.exports;p()(M,{VBtn:f["a"],VCard:v["a"],VCol:A["a"],VDialog:q["a"],VForm:m["a"],VIcon:g["a"],VRow:F["a"],VSelect:y["a"],VTextField:w["a"]});var E={props:{data:{type:Object,required:!0}},data:()=>({noListHeaders:null,sendingListHeaders:!1,addDialog:!1}),computed:{moduleId(){return this.$route.params.module},fieldsTypes(){return this.$rest("modulesFieldsTypes").list.map(({id:e,name:t})=>({text:t,value:e}))},listHeaders:{get(){return this.data.viewOptions.listHeaders},set(e){this.data.viewOptions.listHeaders=e}}},methods:{validate(){const e=this.$refs.fieldList;return!!e&&(this.listHeaders.length?this.noListHeaders=!1:this.noListHeaders=!0,e.validate())},updateListHeaders(){for(let e of this.listHeaders)this.fields.find(({key:t})=>t===e)||this.listHeaders.splice(this.listHeaders.indexOf(e),1)},sendListHeaders(){this.sendingListHeaders=!0,this.$rest("modules").put({id:this.moduleId,data:{value:{listHeaders:this.data.viewOptions.listHeaders}},prop:"viewOptions"}).finally(()=>{this.sendingListHeaders=!1})}},watch:{listHeaders(e){e.length&&(this.noListHeaders=!1)}},components:{FieldItem:b,FieldList:I,AddField:P,Tooltip:l["a"]}},W=E,D=(s("c8db"),s("cc20")),N=s("ef9a"),S=Object(h["a"])(W,i,a,!1,null,null,null);t["default"]=S.exports;p()(S,{VBtn:f["a"],VChip:D["a"],VChipGroup:N["a"],VIcon:g["a"]})},b066:function(e,t,s){},c8db:function(e,t,s){"use strict";s("b066")},ef9a:function(e,t,s){"use strict";s("8f5a"),s("608c");var i=s("9d26"),a=s("0789"),r=s("604c"),n=s("e4cd"),l=s("dc22"),o=s("c3f0"),d=s("58df");const c=Object(d["a"])(r["a"],n["a"]).extend({name:"base-slide-group",directives:{Resize:l["a"],Touch:o["a"]},props:{activeClass:{type:String,default:"v-slide-item--active"},centerActive:Boolean,nextIcon:{type:String,default:"$next"},prevIcon:{type:String,default:"$prev"},showArrows:{type:[Boolean,String],validator:e=>"boolean"===typeof e||["always","desktop","mobile"].includes(e)}},data:()=>({internalItemsLength:0,isOverflowing:!1,resizeTimeout:0,startX:0,scrollOffset:0,widths:{content:0,wrapper:0}}),computed:{__cachedNext(){return this.genTransition("next")},__cachedPrev(){return this.genTransition("prev")},classes(){return{...r["a"].options.computed.classes.call(this),"v-slide-group":!0,"v-slide-group--has-affixes":this.hasAffixes,"v-slide-group--is-overflowing":this.isOverflowing}},hasAffixes(){switch(this.showArrows){case"always":return!0;case"desktop":return!this.isMobile;case!0:return this.isOverflowing||Math.abs(this.scrollOffset)>0;case"mobile":return this.isMobile||this.isOverflowing||Math.abs(this.scrollOffset)>0;default:return!this.isMobile&&(this.isOverflowing||Math.abs(this.scrollOffset)>0)}},hasNext(){if(!this.hasAffixes)return!1;const{content:e,wrapper:t}=this.widths;return e>Math.abs(this.scrollOffset)+t},hasPrev(){return this.hasAffixes&&0!==this.scrollOffset}},watch:{internalValue:"setWidths",isOverflowing:"setWidths",scrollOffset(e){this.$refs.content.style.transform=`translateX(${-e}px)`}},beforeUpdate(){this.internalItemsLength=(this.$children||[]).length},updated(){this.internalItemsLength!==(this.$children||[]).length&&this.setWidths()},methods:{genNext(){const e=this.$scopedSlots.next?this.$scopedSlots.next({}):this.$slots.next||this.__cachedNext;return this.$createElement("div",{staticClass:"v-slide-group__next",class:{"v-slide-group__next--disabled":!this.hasNext},on:{click:()=>this.onAffixClick("next")},key:"next"},[e])},genContent(){return this.$createElement("div",{staticClass:"v-slide-group__content",ref:"content"},this.$slots.default)},genData(){return{class:this.classes,directives:[{name:"resize",value:this.onResize}]}},genIcon(e){let t=e;this.$vuetify.rtl&&"prev"===e?t="next":this.$vuetify.rtl&&"next"===e&&(t="prev");const s=`${e[0].toUpperCase()}${e.slice(1)}`,a=this["has"+s];return this.showArrows||a?this.$createElement(i["a"],{props:{disabled:!a}},this[t+"Icon"]):null},genPrev(){const e=this.$scopedSlots.prev?this.$scopedSlots.prev({}):this.$slots.prev||this.__cachedPrev;return this.$createElement("div",{staticClass:"v-slide-group__prev",class:{"v-slide-group__prev--disabled":!this.hasPrev},on:{click:()=>this.onAffixClick("prev")},key:"prev"},[e])},genTransition(e){return this.$createElement(a["d"],[this.genIcon(e)])},genWrapper(){return this.$createElement("div",{staticClass:"v-slide-group__wrapper",directives:[{name:"touch",value:{start:e=>this.overflowCheck(e,this.onTouchStart),move:e=>this.overflowCheck(e,this.onTouchMove),end:e=>this.overflowCheck(e,this.onTouchEnd)}}],ref:"wrapper"},[this.genContent()])},calculateNewOffset(e,t,s,i){const a=s?-1:1,r=a*i+("prev"===e?-1:1)*t.wrapper;return a*Math.max(Math.min(r,t.content-t.wrapper),0)},onAffixClick(e){this.$emit("click:"+e),this.scrollTo(e)},onResize(){this._isDestroyed||this.setWidths()},onTouchStart(e){const{content:t}=this.$refs;this.startX=this.scrollOffset+e.touchstartX,t.style.setProperty("transition","none"),t.style.setProperty("willChange","transform")},onTouchMove(e){this.scrollOffset=this.startX-e.touchmoveX},onTouchEnd(){const{content:e,wrapper:t}=this.$refs,s=e.clientWidth-t.clientWidth;e.style.setProperty("transition",null),e.style.setProperty("willChange",null),this.$vuetify.rtl?this.scrollOffset>0||!this.isOverflowing?this.scrollOffset=0:this.scrollOffset<=-s&&(this.scrollOffset=-s):this.scrollOffset<0||!this.isOverflowing?this.scrollOffset=0:this.scrollOffset>=s&&(this.scrollOffset=s)},overflowCheck(e,t){e.stopPropagation(),this.isOverflowing&&t(e)},scrollIntoView(){if(!this.selectedItem&&this.items.length){const e=this.items[this.items.length-1].$el.getBoundingClientRect(),t=this.$refs.wrapper.getBoundingClientRect();(this.$vuetify.rtl&&t.right<e.right||!this.$vuetify.rtl&&t.left>e.left)&&this.scrollTo("prev")}this.selectedItem&&(0===this.selectedIndex||!this.centerActive&&!this.isOverflowing?this.scrollOffset=0:this.centerActive?this.scrollOffset=this.calculateCenteredOffset(this.selectedItem.$el,this.widths,this.$vuetify.rtl):this.isOverflowing&&(this.scrollOffset=this.calculateUpdatedOffset(this.selectedItem.$el,this.widths,this.$vuetify.rtl,this.scrollOffset)))},calculateUpdatedOffset(e,t,s,i){const a=e.clientWidth,r=s?t.content-e.offsetLeft-a:e.offsetLeft;s&&(i=-i);const n=t.wrapper+i,l=a+r,o=.4*a;return r<=i?i=Math.max(r-o,0):n<=l&&(i=Math.min(i-(n-l-o),t.content-t.wrapper)),s?-i:i},calculateCenteredOffset(e,t,s){const{offsetLeft:i,clientWidth:a}=e;if(s){const e=t.content-i-a/2-t.wrapper/2;return-Math.min(t.content-t.wrapper,Math.max(0,e))}{const e=i+a/2-t.wrapper/2;return Math.min(t.content-t.wrapper,Math.max(0,e))}},scrollTo(e){this.scrollOffset=this.calculateNewOffset(e,{content:this.$refs.content?this.$refs.content.clientWidth:0,wrapper:this.$refs.wrapper?this.$refs.wrapper.clientWidth:0},this.$vuetify.rtl,this.scrollOffset)},setWidths(){window.requestAnimationFrame(()=>{const{content:e,wrapper:t}=this.$refs;this.widths={content:e?e.clientWidth:0,wrapper:t?t.clientWidth:0},this.isOverflowing=this.widths.wrapper<this.widths.content,this.scrollIntoView()})}},render(e){return e("div",this.genData(),[this.genPrev(),this.genWrapper(),this.genNext()])}});c.extend({name:"v-slide-group",provide(){return{slideGroup:this}}});var h=s("a9ad");t["a"]=Object(d["a"])(c,h["a"]).extend({name:"v-chip-group",provide(){return{chipGroup:this}},props:{column:Boolean},computed:{classes(){return{...c.options.computed.classes.call(this),"v-chip-group":!0,"v-chip-group--column":this.column}}},watch:{column(e){e&&(this.scrollOffset=0),this.$nextTick(this.onResize)}},methods:{genData(){return this.setTextColor(this.color,{...c.options.methods.genData.call(this)})}}})}}]);
//# sourceMappingURL=chunk-23e5b5d3.35a9ba96.js.map