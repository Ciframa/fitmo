import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import VueNumberInput from "@chenfengyuan/vue-number-input";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faAngleDown,
  faBars,
  faCheck,
  faStar,
  faXmark,
} from "@fortawesome/free-solid-svg-icons";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faBars);
library.add(faXmark);
library.add(faStar);
library.add(faCheck);
library.add(faAngleDown);
library.add(faStar);

createApp(App)
  .use(router)
  .component(VueNumberInput.name, VueNumberInput)
  .component("font-awesome-icon", FontAwesomeIcon)
  .mount("#app");
