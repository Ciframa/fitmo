<template>
  <div class="product">
    <div class="administration">
      <div class="sideBar">
        <ul>
          <li v-on:click="showedMain = 1">Obecné</li>
          <li v-on:click="showedMain = 2">Skladovost</li>
        </ul>
      </div>
      <div class="admin__add-product mainWrapper">
        <h2>Editace produktu</h2>
        <template v-if="showedMain === 1">
          <h3>Obecné informace o produktu</h3>
          <label for="add-product-name">Název produktu</label>
          <input
            id="add-product-name"
            type="text"
            placeholder="Název produktu..."
            v-model="this.product.name"
          />
          <input
            id="add-product-subName"
            type="text"
            placeholder="Popisek produktu..."
            v-model="this.product.description"
          />
          <template v-if="!this.product.hasChildren">
            <label>Zadej, jestli patří pod nějaký produkt</label>
            <select v-model="this.product.parent" :key="product.id">
              <option selected value="0"></option>
              <option
                v-for="product in products"
                :value="product"
                :key="product.id"
              >
                {{ product.name }}
              </option>
            </select>
          </template>
          <template v-if="this.product.hasChildren">
            <p>Nevidíš možnost přidání parenta z důvodu, že je to Parent</p>
          </template>
          <template v-if="!this.product.hasChildren">
            <label>Zadej variantu která se bude prodávat</label>
            <input v-model="this.product.variant" type="text" />
          </template>

          <template
            v-if="this.product.parent?.color_id || this.product.color_id"
          >
            <label>Zadej barvu</label>
            <input
              v-model="this.product.color_primary"
              type="color"
              :style="{ backgroundColor: this.product.color_primary }"
            />
            <input
              v-model="this.product.color_secondary"
              type="color"
              :style="{ backgroundColor: this.product.color_secondary }"
            />
            <input
              id="add-product-colorName"
              type="text"
              placeholder="Barva produktu..."
              v-model="this.product.color_name"
            />
          </template>
          <label>Zadej cenu</label>
          <input type="number" v-model="this.product.price" />
          <div class="my-row" v-if="this.product.discount">
            <label>Zadej zlevněnou cenu</label>
            <input
              v-on:change="
                this.product.discountedPercent = this.product.discounted
                  ? 100 -
                    Math.round(
                      (this.product.discounted / this.product.price) * 100
                    )
                  : ''
              "
              type="number"
              v-model="this.product.discounted"
            />
            <label>Zadej zlevněnou cenu %</label>
            <input
              type="number"
              v-on:change="
                this.product.discounted = this.product.discountPercent
                  ? Math.round(
                      (this.product.price / 100) *
                        (100 - this.product.discountedPercent)
                    )
                  : ''
              "
              v-model="this.product.discountedPercent"
            />
          </div>

          <p>Kategorie produktu:</p>

          <template v-if="product.parent_id === 0">
            <!-- Start with just one select at the top level -->
            <div
              :key="category.id"
              v-for="(category, categoryIndex) in product.categories"
              class="my-row category-my-row"
              v-show="category?.categoryStatus !== 'deleted'"
            >
              {{ category.categoryId }}
              <div>
                {{ category?.name }}
                <div
                  class="my-row"
                  v-if="groupedCategories && category?.categoryStatus"
                >
                  <CategorySelect
                    :category="groupedCategories"
                    :product="product"
                    @category-selected="onCategorySelected"
                    :index="categoryIndex"
                  />
                </div>
              </div>
              <font-awesome-icon
                :icon="['fa', 'times']"
                :onClick="
                  () => {
                    if (this.product.categories[categoryIndex].categoryStatus) {
                      // Remove the category at index `categoryIndex`
                      this.product.categories.splice(categoryIndex, 1);
                    } else {
                      this.product.categories[categoryIndex].categoryStatus =
                        'deleted';
                    }
                  }
                "
              />
            </div>
            <font-awesome-icon
              :icon="['fa', 'plus']"
              :onClick="
                () => {
                  this.product.categories.push({
                    categoryId: 0,
                    categoryStatus: 'added',
                  });
                }
              "
            />
          </template>
          <div class="my-row admin__stateButtons">
            <span
              :class="product.discount ? 'btn-yellow' : 'btn-yellow-notActive'"
              v-on:click="product.discount = !product.discount"
              >Akce</span
            >
            <span
              :class="product.topProduct ? 'btn-green' : 'btn-green-notActive'"
              v-on:click="product.topProduct = !product.topProduct"
              >Top produkt</span
            >
            <span
              :class="product.newProduct ? 'btn-blue' : 'btn-blue-notActive'"
              v-on:click="product.newProduct = !product.newProduct"
              >Novinka</span
            >
          </div>
          <div>
            <div class="addPhoto__wrapper">
              <div
                class="addPhoto__wrapper__checkbox"
                v-on:click="fileCropping = !fileCropping"
              >
                <font-awesome-icon
                  v-if="fileCropping"
                  :icon="['fa', 'check']"
                  class="addPhoto__wrapper__checkbox__svg"
                />

                <span v-if="fileCropping">S ořezem</span>
                <span v-if="!fileCropping">Bez ořezu</span>
              </div>
            </div>
            <upload-images
              v-if="fileCropping"
              ref="cropper"
              @updateReview="(value) => (this.imageReview = value)"
            ></upload-images>
            <input type="file" v-if="!fileCropping" v-on:change="addPhoto2" />
            <form
              class="admin__add-product"
              @submit.prevent="addProductPhoto($event)"
            >
              <div class="addPhoto__tentativePhotos">
                <label
                  v-for="(image, index) in product.photos"
                  v-show="
                    image?.state !== 'toDeleteFromDb' &&
                    image?.state !== 'deleted'
                  "
                  :key="image.id"
                >
                  <img
                    :src="
                      image?.state !== 'fromDb'
                        ? image.tentativePath
                        : product.color_name
                        ? this.imagesBasePath +
                          product.name +
                          '-' +
                          product.color_name +
                          '/' +
                          image.image_path
                        : product.variant
                        ? this.imagesBasePath +
                          product.name +
                          '-' +
                          product.variant +
                          '/' +
                          image.image_path
                        : this.imagesBasePath +
                          product.name +
                          '/' +
                          image.image_path
                    "
                  />
                  <div class="buttonsWrapper">
                    <input type="checkbox" :id="index" v-model="image.isMain" />
                    <button
                      type="button"
                      class="delete"
                      v-on:click="this.deleteImage(index)"
                    >
                      X
                    </button>
                  </div>
                </label>
              </div>
              <input type="submit" value="Přidat fotku" class="btn-yellow" />
            </form>
          </div>
        </template>

        <template v-if="showedMain === 2"
          ><h3>Skladovost produktu</h3>
          <div class="my-row">
            <div class="my-row manageStock">
              <label for="manageStock">
                <input
                  type="checkbox"
                  v-model="this.product.manageStock"
                  id="manageStock"
                />
                <font-awesome-icon :icon="['fa', 'check']" />
                Spravovat sklad?
              </label>
            </div>
            <template v-if="this.product.manageStock">
              <label for="add-product-stock">Skladovost produktu</label>
              <input
                id="add-product-stock"
                type="number"
                placeholder="Skladovost produktu..."
                v-model="this.product.stock"
              />
              <label>Povolit nákup na objednávku?</label>
              <select v-model="this.product.stockInformation">
                <option value="allow">Povolit</option>
                <option value="notAllowed">Nepovolovat</option>
                <option value="allowButInform">
                  Povolit, ale informovat zákazníka
                </option>
              </select>
            </template>
            <template v-if="!this.product.manageStock">
              <select v-model="this.product.stockInformation">
                <option value="onStock">Skladem</option>
                <option value="notOnStock">Není skladem</option>
                <option value="onOrder">Na objednávku</option>
              </select>
            </template>
          </div>
        </template>
        <input
          type="submit"
          value="Změnit produkt"
          class="btn-yellow"
          :onClick="editProduct"
        />
      </div>
    </div>
    <div class="product__header">
      <div class="product__header__item my-row" :key="product.id">
        <div class="product__header__item__header">
          <h1 v-if="!this.product.color_name">{{ this.product.name }}</h1>
          <h1 v-if="this.product.color_name">
            {{ this.product.name }}, {{ this.product.color_name }}
          </h1>
          <h2>{{ this.product.description }}</h2>
          <div class="product__header__rating">
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
            <font-awesome-icon :icon="['fa', 'star']" />
          </div>
        </div>
        <div class="product__header__item__img_wrapper">
          <ImagesSlider
            :images="product.image_urls"
            :imageBasePath="this.imagesBasePath"
            :productName="product.tempName"
            :productVariant="product.variant"
            :colorName="product.color_name"
          />
          <span
            v-if="product.discountPercent"
            class="home__eshop__wrapper__item_discount"
            >-{{ product.discountPercent }}%</span
          >
        </div>

        <div class="product__header__item__footer">
          <p class="product__header__item__footer__info">
            Stručný popis ve dvou až 3 větách. Stručný popis ve dvou až 3
            větách. Stručný popis ve dvou až 3 větách.
          </p>
          <div class="home__eshop__wrapper__discounts">
            <span v-if="product.discount" class="btn-yellow">Akce</span>
            <span v-if="product.topProduct" class="btn-green">Top produkt</span>
            <span v-if="product.newProduct" class="btn-blue">Novinka</span>
          </div>
          <div v-if="product.discounted" class="home__eshop__wrapper__price">
            <span class="home__eshop__wrapper__price__trough"
              >{{ product.price }} Kč</span
            >
            <span class="home__eshop__wrapper__price__discount"
              >{{ product.discounted }} Kč</span
            >
          </div>
          <div v-if="!product.discounted" class="home__eshop__wrapper__price">
            <span>{{ product.price }} Kč</span>
          </div>
        </div>
      </div>
    </div>
    <div class="product__description">
      <input
        type="submit"
        value="Přidat šablonu"
        class="btn-green"
        v-if="this.product.parent_id === 0"
        :onClick="() => pushTemplate(0)"
      />
      <div
        v-for="(template, index) in templates"
        :key="template.id"
        class="product__description__templateWrapper"
      >
        <div class="actions" :class="{ edit: template.mode === 'edit' }">
          <input
            type="submit"
            value="Uložit aktuální stav šablon"
            class="btn-yellow"
            v-if="template.mode === 'edit'"
            :onClick="addTemplate"
          />
          <input
            type="submit"
            :value="
              template.mode === 'edit' ? 'Zrušit editaci' : 'Upravit šablonu'
            "
            class="btn-blue editFromDb"
            :onClick="
              () =>
                template.mode === 'edit'
                  ? (template.mode = 'read')
                  : (template.mode = 'edit')
            "
            v-if="template.from === 'db'"
          />
          <input
            type="submit"
            value="X"
            class="btn-red deleteFromDb"
            :onClick="() => deleteTemplateFromDb(template)"
            v-if="template.from === 'db'"
          />
        </div>
        <template v-if="template.from !== 'db' || template.mode === 'edit'">
          <select v-model="template.type">
            <option value=""></option>
            <option value="fotkaText">Fotka | Text</option>
            <option value="textFotka">Text | Fotka</option>
            <option value="textFotky">Text | Více fotek</option>
            <option value="fotkyText">Více fotek | Text</option>
            <option value="jenFotka">Jen fotka</option>
            <option value="jenText">Jen text</option>
            <option value="fotkaFotka">Fotka | Fotka</option>
            <option value="textText">Text | Text</option>
            <option value="textNaFotce">Text na fotce</option>
            <option value="bloky">Bloky</option>
            <option value="youtubeVideo">YT video</option>
          </select>
          <div class="my-row">
            <div class="column">
              <input
                type="text"
                v-if="
                  template.type !== 'youtubeVideo' &&
                  template.type !== 'fotkaFotka' &&
                  template.type !== 'textText'
                "
                v-model="template.color"
              />
              <template
                v-if="
                  template.type !== 'jenFotka' &&
                  template.type !== 'bloky' &&
                  template.type !== 'youtubeVideo' &&
                  template.type !== 'fotkaFotka'
                "
              >
                <quill-editor
                  v-model:value="template.text"
                  class="editor"
                  @change="(value) => (template.text = value.html)"
                />
              </template>
              <quill-editor
                v-if="template.type === 'textText'"
                v-model:value="template.text2"
                class="editor"
                @change="(value) => (template.text2 = value.html)"
              />
            </div>
            <div class="column" v-if="template.type !== 'jenText'">
              <div>
                <input
                  v-if="template.type !== 'youtubeVideo'"
                  type="file"
                  @change="(event) => addPhoto(event, 1, template)"
                />
                <input
                  v-if="template.type === 'fotkaFotka'"
                  type="file"
                  @change="(event) => addPhoto(event, 2, template)"
                />
                <input
                  type="text"
                  v-model="template.txt1"
                  :placeholder="
                    template.type !== 'youtubeVideo'
                      ? 'Hlavní popis'
                      : 'Link na video'
                  "
                  v-if="
                    template.type === 'textFotky' ||
                    template.type === 'fotkyText' ||
                    template.type === 'bloky' ||
                    template.type === 'youtubeVideo'
                  "
                />
                <input
                  type="text"
                  v-model="template.subtxt1"
                  placeholder="Popisek bloku"
                  v-if="
                    template.type === 'textFotky' ||
                    template.type === 'fotkyText' ||
                    template.type === 'bloky'
                  "
                />
              </div>
              <template
                v-if="
                  template.type === 'textFotky' ||
                  template.type === 'fotkyText' ||
                  template.type === 'bloky'
                "
              >
                <div v-for="i in 5" :key="i">
                  <input
                    type="file"
                    @change="(event) => addPhoto(event, i + 1, template)"
                  />
                  <input
                    placeholder="Hlavní popis"
                    type="text"
                    v-model="template[`txt${i + 1}`]"
                  />
                  <input
                    type="text"
                    v-model="template[`subtxt${i + 1}`]"
                    placeholder="Popisek bloku"
                    v-if="
                      template.type === 'textFotky' ||
                      template.type === 'fotkyText' ||
                      template.type === 'bloky'
                    "
                  />
                </div>
              </template>
            </div>
          </div>
        </template>

        <template-product :product="this.product" :template="{ template }" />

        <div class="my-row flex-end" v-if="template.from !== 'db'">
          <input
            type="submit"
            value="Smazat šablonu"
            class="btn-red"
            :onClick="() => deleteTemplate(index)"
          />
          <input
            type="submit"
            value="Uložit šablonu"
            class="btn-yellow"
            :onClick="addTemplate"
          />
        </div>
        <input
          type="submit"
          value="Přidat šablonu"
          v-if="this.product.parent_id === 0"
          class="btn-green"
          :onClick="() => pushTemplate(index + 1)"
        />
      </div>
      <div class="product__description__template"></div>
    </div>
  </div>
</template>

<script>
import ImagesSlider from "../../components/ImagesSlider.vue";
import axios from "../../api";
import TemplateProduct from "../../components/admin/TemplateProduct.vue";
import { quillEditor } from "vue3-quill";
import Product from "@/components/Product.vue";
import UploadImages from "@/components/UploadImages.vue";
import CategorySelect from "@/views/admin/CategorySelect.vue";

export default {
  components: {
    CategorySelect,
    UploadImages,
    Product,
    ImagesSlider,
    quillEditor,
    TemplateProduct,
  },
  data() {
    return {
      photos: [],
      product: {},
      products: {},
      templates: [],
      categories: [],
      fileCropping: false,
      groupedCategories: [],
      showedMain: 1,
      // imagesBasePath: `https://be.fitmo.cz/products/`,
      imagesBasePath: `https://be.fitmo.cz/products/`,
    };
  },

  methods: {
    loggedUser() {
      return JSON.parse(sessionStorage.getItem("user"));
    },
    onCategorySelected({ index, id }) {
      this.product.categories[index].categoryId = id;
    },
    addPhoto2(event) {
      const uploadedFile = event.target.files[0]; // Get the selected file
      if (uploadedFile) {
        var reader = new FileReader();
        reader.readAsDataURL(uploadedFile);

        reader.onload = () => {
          // Use army-row function here
          let file = new File([reader.result], uploadedFile.name);
          let newProduct = {
            file: uploadedFile,
            image_path: uploadedFile.name,
            tentativePath: URL.createObjectURL(uploadedFile),
            isMain: false,
            state: "added",
          };
          this.product?.photos?.push(newProduct);
        };

        reader.onerror = (error) => {
          // Use army-row function here
          console.log("Error: ", error);
        };
      }
    },
    deleteImage(index) {
      if (this.product.photos[index].state === "fromDb") {
        this.product.photos[index].state = "toDeleteFromDb";
      } else {
        this.product.photos[index].state = "deleted";
      }
    },
    addProductPhoto() {
      if (!this.product.newProduct) {
        this.product.newProduct = new FormData();
      }
      let imageName = this.$refs.cropper.$refs.upload.files[0].name;
      let img = this.$refs.cropper.$refs.cropper.getResult();

      let file;

      let newProduct;

      img.canvas.toBlob((blob) => {
        file = new File([blob], imageName);
        newProduct = {
          file: file,
          image_path: imageName,
          tentativePath: this.imageReview,
          isMain: false,
          state: "added",
        };
        this.product?.photos?.push(newProduct);
      }, "image/png");
    },

    addPhoto(event, i, template) {
      const uploadedFile = event.target.files[0]; // Get the selected file
      if (uploadedFile) {
        var reader = new FileReader();
        reader.readAsDataURL(uploadedFile);

        reader.onload = () => {
          // Use army-row function here
          let file = new File([reader.result], uploadedFile.name);
          let newPhoto = {
            file: uploadedFile,
            image_path: uploadedFile.name,
            tentativePath: URL.createObjectURL(uploadedFile),
          };
          console.log(newPhoto);
          template[`image${i}`] = newPhoto;
        };

        reader.onerror = (error) => {
          // Use army-row function here
          console.log("Error: ", error);
        };
      }
    },
    deleteTemplate(index) {
      this.templates.splice(index, 1);
    },
    deleteTemplateFromDb(template) {
      axios
        .delete(
          `/api/product/${this.product.id}/template/${template.id}`,
          this.product,
          {
            headers: {
              Authorization: "",
            },
          }
        )
        .then((response) => {
          if (response.status == 200) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
            this.getTemplates();
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },

    pushTemplate(index) {
      this.templates.splice(index, 0, {
        type: "",
        text: "",
        text2: "",
        from: "created",
        image1: "",
        image2: "",
        image3: "",
        image4: "",
        image5: "",
        image6: "",
        txt1: "",
        txt2: "",
        txt3: "",
        txt4: "",
        txt5: "",
        txt6: "",
        subtxt1: "",
        subtxt2: "",
        subtxt3: "",
        subtxt4: "",
        subtxt5: "",
        subtxt6: "",
        color: "#FFFFFF",
      });
    },
    groupByKeyReduce(arr) {
      if (arr.length === 0) return;
      var map = {};
      var array = arr;
      for (var i = 0; i < array.length; i++) {
        var obj = array[i];
        obj.children = [];
        map[obj.id] = obj;
        var parent = obj.id_parent || "-";
        if (!map[parent]) {
          map[parent] = {
            children: [],
          };
        }
        map[parent].children.push(obj);
      }
      return map["-"].children;
    },
    async getCategories() {
      try {
        const response = await axios.post("/api/categories");
        this.groupedCategories = this.groupByKeyReduce(response.data);
        this.categories = response.data;
      } catch (error) {
        console.log(error);
      }
    },

    getCategoryName(categoryId) {
      const category = this.categories.find((item) => item.id === categoryId);
      return category ? category?.name : "";
    },
    async getMainProducts() {
      try {
        const response = await axios.get("/api/mainProducts");
        this.products = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async getTemplates() {
      try {
        const response = await axios.get(
          `/api/product/${this.product.id}/getTemplates`
        );
        this.templates = response.data.map((item) => {
          item.from = "db";
          return item;
        });
        console.log(this.templates);
      } catch (error) {
        console.log(error);
      }
    },
    async addTemplate() {
      let hasError = false;

      this.templates.forEach((template) => {
        if (!template.type) {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Asi jsi něco nevyplnil sire, zkus na to mrknout",
          });
          hasError = true;
        }
      });

      if (hasError) {
        // Return something indicating an error
        return "Error occurred";
      }
      axios
        .post(
          `api/product/${this.product.id}/template/store`,
          [this.templates, this.product],
          {
            headers: {
              //  Authorization: this.tokenData.data.token,
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          if (response.status == 200) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
            this.getTemplates();
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },
    async editProduct() {
      await axios
        .post("/api/product/" + this.product.id, this.product, {
          headers: {
            //  Authorization: this.tokenData.data.token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.status == 200) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          } else {
            this.$snackbar.add({
              type: "error",
              text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
            });
          }
          this.callEverything();
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },
    async getProduct() {
      try {
        const response = await axios.get(
          "/api/productsByIds/" + [this.$route.params.productId]
        );
        this.product = response.data[0];
        this.product.manageStock = response.data[0].manageStock === 1;
        //new photos
        this.product.photos = response.data[0].images.map((image) => {
          return { ...image, state: "fromDb", isMain: image.is_main === 1 };
        });
        this.product.tempName = this.product.name;
        this.product.categoryName = this.getCategoryName(
          this.product.category_id
        );
      } catch (error) {
        console.log(error);
      }
    },
    async pairParent() {
      let parent = this.products.find((parentItem) => {
        return parentItem.id === this.product.parent_id && parentItem.id !== 0;
      });

      if (!parent) {
        this.product.parent = {
          category_id: 0,
          id: 0,
          category_name: "",
        };
      } else {
        this.product = { ...this.product, parent };
      }
    },
    callEverything() {
      this.getProduct().then(() => {
        this.getMainProducts().then(() => {
          this.pairParent();
        });
        this.getTemplates();
      });
      this.getCategories();
      // this.getMainCategories();
    },
  },

  mounted() {
    this.callEverything();
  },
};
</script>

<style scoped lang="scss"></style>
<style lang="scss">
.category-my-row {
  gap: 1rem;
  align-items: center;
  padding: 0.3rem 0;
}

.manageStock {
  svg {
    padding: 0.3rem;
    border: 1px solid black;
  }

  svg {
    color: white;
  }

  input:checked + svg {
    color: black;
  }
}

.product__description {
  &__templateWrapper {
    position: relative;
    padding: 3rem 0;

    .actions {
      display: flex;
      gap: 1rem;
      position: absolute;
      top: 4rem;
      right: 1rem;
      z-index: 1;
    }

    .edit {
      top: 0;
    }

    .deleteFromDb {
      height: 4rem;
      width: 4rem;
      padding: 0;
      border-radius: 50%;
    }
  }

  &__template {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;

    select {
      width: 15rem;
    }

    input[type="submit"] {
      margin-left: 2rem;
    }

    &__wrapper {
      width: 100%;
      border: 1px solid black;
    }

    .column {
      gap: 1rem;
    }
  }

  .column {
    display: flex;
    flex-direction: column;
  }

  .flex-end {
    justify-content: flex-end;
    gap: 1rem;

    & > * {
      padding: 2rem;
    }
  }
}
</style>
