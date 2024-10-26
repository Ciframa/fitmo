<template>
  <div>
    <form class="admin__add-product" @submit.prevent="addPhoto($event)">
      <h2>Přidání produktu</h2>
      <label for="add-product-name">Název produktu</label>
      <input
        id="add-product-name"
        type="text"
        placeholder="Název produktu..."
        v-model="product.name"
      />
      <input
        id="add-product-subName"
        type="text"
        placeholder="Popisek produktu..."
        v-model="product.subName"
      />
      <label>Zadej, jestli patří pod nějaký produkt</label>
      <select v-model="this.product.parent" :key="product.id">
        <option selected value="0"></option>
        <option v-for="product in products" :value="product" :key="product.id">
          {{ product.name }}
        </option>
      </select>
      <label>Zadej variantu která se bude prodávat</label>
      <input v-model="this.product.variant" type="text" />
      <template v-if="this.product.parent.color_id !== null">
        <label>Zadej barvu</label>
        <input
          v-model="this.product.colors.primary"
          type="color"
          :style="{ backgroundColor: this.product.colors.primary }"
        />
        <input
          v-model="this.product.colors.secondary"
          type="color"
          :style="{ backgroundColor: this.product.colors.secondary }"
        />
        <input
          id="add-product-colorName"
          type="text"
          placeholder="Barva produktu..."
          v-model="this.product.colors.colorName"
        />
      </template>
      <label>Zadej cenu</label>
      <input type="text" v-model="this.product.prices.normal" />
      <div class="row" v-if="this.product.isDiscount">
        <label>Zadej zlevněnou cenu</label>
        <input
          v-on:change="
            this.product.prices.discountedPercent =
              100 -
              Math.round(
                (this.product.prices.discounted / this.product.prices.normal) *
                  100
              )
          "
          type="text"
          v-model="this.product.prices.discounted"
        />
        <label>Zadej zlevněnou cenu %</label>
        <input
          type="text"
          v-on:change="
            this.product.prices.discounted = Math.round(
              (this.product.prices.normal / 100) *
                (100 - this.product.prices.discountedPercent)
            )
          "
          v-model="this.product.prices.discountedPercent"
        />
      </div>
      <label>Sleva?</label>
      <input
        type="checkbox"
        v-model="this.product.isDiscount"
        class="isDiscount"
        :class="this.product.isDiscount ? 'active' : ''"
      />
      <label
        >Kategorie produktu:
        {{ getCategoryName(this.product.parent.category_id) }}</label
      >
      <template v-if="product.parent.id === 0">
        <div
          v-for="(productCategory, categoriesIndex) in this.product.categories"
          :key="categoriesIndex"
        >
          {{ productCategory }}
          <select
            :disabled="product.parent.id !== 0"
            :name="'add-product-category-' + categoriesIndex"
            :id="'add-product-category-' + categoriesIndex"
            :v-model="productCategory.categoryId"
            v-for="(subCategory, index) in categoriesSelects"
            :key="subCategory.id"
            :onChange="
              (e) => {
                productCategory.categoryId = e.target.value;
                this.getSubCategory(e.target.value, index, categoriesIndex);
              }
            "
          >
            <option disabled selected></option>
            <option
              v-for="category in subCategory"
              :value="category.id"
              :key="category.id"
            >
              {{ category.name }}
            </option>
          </select>
        </div>
        <font-awesome-icon
          :icon="['fa', 'plus']"
          :onClick="
            () => {
              this.product.categories.push({ categoryId: 0 });
            }
          "
        />
      </template>
      <div class="row admin__stateButtons">
        <span
          :class="
            product.stateButtons.discount
              ? 'btn-yellow'
              : 'btn-yellow-notActive'
          "
          v-on:click="
            product.stateButtons.discount = !product.stateButtons.discount
          "
          >Akce</span
        >
        <span
          :class="
            product.stateButtons.topProduct
              ? 'btn-green'
              : 'btn-green-notActive'
          "
          v-on:click="
            product.stateButtons.topProduct = !product.stateButtons.topProduct
          "
          >Top produkt</span
        >
        <span
          :class="
            product.stateButtons.newProduct ? 'btn-blue' : 'btn-blue-notActive'
          "
          v-on:click="
            product.stateButtons.newProduct = !product.stateButtons.newProduct
          "
          >Novinka</span
        >
      </div>
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
        <upload-images
          v-if="fileCropping"
          ref="cropper"
          @updateReview="(value) => (this.imageReview = value)"
        ></upload-images>
        <input type="file" v-if="!fileCropping" v-on:change="addPhoto2" />
        <div class="home__eshop__wrapper">
          <Product :products="this.product" />
        </div>
      </div>
      <div class="addPhoto__tentativePhotos">
        <label v-for="(image, index) in product.photos" :key="image.id">
          <img :src="image.tentativePath" />
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
    <input
      type="submit"
      value="Přidat produkt"
      class="btn-yellow"
      :onClick="addProduct"
    />
  </div>
</template>

<script>
import axios from "../../api";
import UploadImages from "../../components/UploadImages.vue";
import Product from "../../components/Product.vue";
export default {
  components: { UploadImages, Product },
  data() {
    return {
      fileCropping: false,
      imageReview: "https://be.fitmo.cz/default/default.png",
      categoriesSelects: [],
      product: {
        categories: [{ categoryId: 0 }],
        colors: {
          primary: null,
          secondary: null,
          colorName: "",
        },
        photos: [],
        stateButtons: {
          discount: false,
          topProduct: false,
          newProduct: false,
        },
        parent: {
          category_id: 0,
          id: 0,
          category_name: "",
        },
        isDiscount: false,
        prices: {
          normal: 0,
          discounted: null,
          discountedPercent: null,
        },
        variant: "",
      },
      products: [],
      categories: [],
    };
  },

  methods: {
    async getCategories() {
      try {
        const response = await axios.post("/api/categories");
        this.groupedCategories = this.groupByKeyReduce(response.data);
        this.categories = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    deleteImage(imageId) {
      this.product.photos = this.product.photos.filter(
        (photo, index) => index !== imageId
      );
    },
    getCategoryName(categoryId) {
      const category = this.categories.find((item) => item.id === categoryId);
      return category ? category.name : "";
    },
    addPhoto2(event) {
      console.log(this.product);

      if (!this.product.newProduct) {
        this.product.newProduct = new FormData();
      }

      const uploadedFile = event.target.files[0]; // Get the selected file
      if (uploadedFile) {
        var reader = new FileReader();
        reader.readAsDataURL(uploadedFile);

        reader.onload = () => {
          // Use arrow function here
          let file = new File([reader.result], uploadedFile.name);
          let newProduct = {
            file: uploadedFile,
            image_path: uploadedFile.name,
            tentativePath: URL.createObjectURL(uploadedFile),
            isMain: false,
          };
          this.product?.photos.push(newProduct);
        };

        reader.onerror = (error) => {
          // Use arrow function here
          console.log("Error: ", error);
        };
      }
    },

    addPhoto() {
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
        };
        this.product.photos.push(newProduct);
      }, "image/png");
    },

    addProduct() {
      axios
        .post(`api/product/store`, this.product, {
          headers: {
            //  Authorization: this.tokenData.data.token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.status == 200 || response.status == 201) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },

    async getMainCategories() {
      try {
        const response = await axios.get("/api/mainCategories");
        this.mainCategories = response.data;
        this.categoriesSelects.push(response.data);
      } catch (error) {
        console.log(error);
      }
    },
    async getSubCategory(id, index) {
      this.categoriesSelects.splice(index + 1);
      try {
        const response = await axios.get("/api/subCategory/" + id);
        if (response.data.length) {
          this.categoriesSelects.push(response.data);
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getMainProducts() {
      try {
        const response = await axios.get("/api/mainProducts");
        this.products = response.data;
      } catch (error) {
        console.log(error);
      }
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
  },

  mounted() {
    this.getCategories();
    this.getMainCategories();
    this.getMainProducts();
  },
};
</script>
<style lang="scss">
.admin {
  &__stateButtons {
    span {
      border-radius: 2rem;
      font-size: 1.2rem;
      padding: 0.7rem 1.3rem !important;
      line-height: 1.3rem;
      border: 1px solid transparent;
      margin: 0.2rem;
      font-weight: 400;
      &.btn-yellow-notActive {
        background: #ffffff;
        color: #fdc300;
        border: 1px solid #fdc300;
      }
      &.btn-green-notActive {
        background: #ffffff;
        color: #00b649;
        border: 1px solid #00b649;
      }
      &.btn-blue-notActive {
        background: #ffffff;
        color: #0caff5;
        border: 1px solid #0caff5;
      }
    }
  }
}
.administration {
  form,
  & > div {
    max-width: 1520px;
    margin: auto;
    justify-content: space-between;
    margin-bottom: 3rem;

    label {
      width: 100%;
      font-size: 1.4rem;
      margin-top: 1.2rem;
      display: block;
    }
    input {
      width: 100%;
      padding: 1rem;
    }
  }
  .addPhoto {
    &__wrapper {
      display: flex;
      flex-direction: column;

      &__checkbox {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 10px 0;

        &__svg {
          border: 1px solid black;
          height: 20px;
          width: 20px;
        }
      }
    }

    &__tentativePhotos {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 2rem;

      & > label {
        max-width: 25rem;
        min-width: 21rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;

        input[type="checkbox"] {
          width: unset;
          border: 1px solid black;
          margin: auto;

          &:checked {
            background: green;
          }
        }
        img {
          border: 1px solid black;
        }
      }
    }
  }
  > .btn-yellow {
    padding: 1rem 2rem !important;
    margin-left: auto;
    display: flex;
    justify-content: center;
  }
}
.isDiscount {
  border: 1px solid black;
  display: flex;
  max-width: 2rem;
  max-height: 2rem;
  margin: 0.4rem;

  &.active {
    background: green;
  }
}
input[type="color"] {
  margin-bottom: 1rem;
}
.buttonsWrapper {
  display: flex;
  justify-content: space-around;
}
.delete {
  background: red !important;
  color: white;
  width: 2rem;
  height: 2rem;
}
</style>
<style lang="scss" scoped>
.home__eshop__wrapper {
  width: 33.2rem;
  max-width: 33.2rem;
  border: 1px solid black;
}
</style>
