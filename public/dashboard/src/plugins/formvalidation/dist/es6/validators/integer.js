export default function a(){return{validate(a){if(a.value===""){return{valid:true}}const e=Object.assign({},{decimalSeparator:".",thousandsSeparator:""},a.options);const t=e.decimalSeparator==="."?"\\.":e.decimalSeparator;const r=e.thousandsSeparator==="."?"\\.":e.thousandsSeparator;const o=new RegExp(`^-?[0-9]{1,3}(${r}[0-9]{3})*(${t}[0-9]+)?$`);const n=new RegExp(r,"g");let s=`${a.value}`;if(!o.test(s)){return{valid:false}}if(r){s=s.replace(n,"")}if(t){s=s.replace(t,".")}const i=parseFloat(s);return{valid:!isNaN(i)&&isFinite(i)&&Math.floor(i)===i}}}}