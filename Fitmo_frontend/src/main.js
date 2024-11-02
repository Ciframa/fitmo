import { createApp } from "vue";
import App from "./App.vue";
import store from "./store";
import router from "./router";
import VueNumberInput from "@chenfengyuan/vue-number-input";
import { library } from "@fortawesome/fontawesome-svg-core";
import { far } from "@fortawesome/free-regular-svg-icons";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import VueQuillEditor from "vue-quill-editor";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import "@vueup/vue-quill/dist/vue-quill.bubble.css";
import "@vueup/vue-quill/dist/vue-quill.core.css";

import {
  faAngleDown,
  faAngleLeft,
  faBars,
  faCheck,
  faFile,
  faSignOut,
  faSpinner,
  faStar,
  faXmark,
} from "@fortawesome/free-solid-svg-icons";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { SnackbarService } from "vue3-snackbar";
import "vue3-snackbar/styles";
import { createVuestic } from "vuestic-ui";

library.add(far, fas, fab);
library.add(faBars);
library.add(faXmark);
library.add(faStar);
library.add(faCheck);
library.add(faAngleDown);
library.add(faAngleLeft);
library.add(faStar);
library.add(faSignOut);
library.add(faSpinner);
library.add(faFile);

createApp(App)
  .use(router)
  .use(SnackbarService)
  .use(store)
  .use(createVuestic())
  .use(VueQuillEditor)
  .component("vue-google-autocomplete", VueGoogleAutocomplete)
  .component(VueNumberInput.name, VueNumberInput)
  .component("font-awesome-icon", FontAwesomeIcon)
  .mount("#app");
