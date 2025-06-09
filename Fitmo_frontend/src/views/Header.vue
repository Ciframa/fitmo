<template>
  <header :class="$route.name">
    <!-- Modal -->
    <div
      class="modal fade loginModal"
      id="loginModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="loginModal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body my-row">
            <form class="login" @submit.prevent="login(credentials)">
              <h2>Přihlášení</h2>
              <label for="email">E-mail</label>
              <input
                type="text"
                id="email"
                v-model="this.credentials.email"
                required
              />

              <label for="password">Heslo</label>
              <input
                type="password"
                id="password"
                v-model="this.credentials.password"
                required
              />
              {{ this.loginError }}
              {{ this.loginQuote }}
              <input class="btn-yellow" type="submit" value="Přihlásit se" />
              <!--                    <span>Nebo</span>-->
              <!--                    <a class="btn-blue" href="#">Přihlásit se přes Facebook</a>-->
              <a href="#">Zapomenuté heslo</a>
            </form>
            <div class="registration registration__info">
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <font-awesome-icon :icon="['fa', 'times']" size="2x" />
              </button>
              <h2>Proč se registrovat?</h2>
              <article>
                <p>Ještě nemáš vytvořený účet?</p>
                <p>Zaregistruj se a získej spousty výhod</p>
              </article>
              <ul>
                <li>
                  <img
                    src="../../public/assets/icons/check_transparent.svg"
                    alt=""
                  />
                  <span>Rychlejší nakupování</span>
                </li>
                <li>
                  <img
                    src="../../public/assets/icons/check_transparent.svg"
                    alt=""
                  />
                  <span>Historie všech svých objednávek</span>
                </li>

                <li>
                  <img
                    src="../../public/assets/icons/check_transparent.svg"
                    alt=""
                  />
                  <span>Nemusíš vyplňovat své údaje</span>
                </li>
                <li>
                  <img
                    src="../../public/assets/icons/check_transparent.svg"
                    alt=""
                  />
                  <span>FITMO novinky z první ruky</span>
                </li>
              </ul>
              <div class="no-acc">
                <a href="/registrace" class="btn-black"
                  >Chci se zaregistrovat</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content_wrapper">
      <div class="content_wrapper__upper">
        <a href="/">
          <img src="../../public/assets/logo.png" alt="Fitmo logo" />
        </a>
        <ul class="content_wrapper__upper__navigation-links">
          <!--<li>O NÁS</li>-->
          <li>
            <router-link to="/info/doprava">DOPRAVA A PLATBA</router-link>
          </li>
          <!--<li>REFERENCE</li>-->
          <li>
            <router-link to="/info/kontakt">KONTAKT</router-link>
          </li>
        </ul>
        <div class="content_wrapper__right__icons__absolute">
          <div class="content_wrapper__right__icons__absolute__price_wrapper">
            <!--<span>
              {{ activePrice }}
              <img
                src="../../public/assets/icons/path_down.svg"
                alt="Path down"
              />
              <ul>
                <li
                  v-on:click="activePrice = 'CZK'"
                  :class="{ active: activePrice === 'CZK' }"
                >
                  <font-awesome-icon :icon="['fa', 'check']" />
                  <label>CZK</label>
                </li>
                <li
                  v-on:click="activePrice = 'EUR'"
                  :class="{ active: activePrice === 'EUR' }"
                >
                  <font-awesome-icon :icon="['fa', 'check']" />
                  <label>EUR</label>
                </li>
              </ul>
            </span>
            -->
          </div>

          <div
            class="content_wrapper__right__icons__absolute__loggedUser"
            v-if="this.loggedUser"
          >
            <span>{{ this.loggedUser?.name }}</span>
            <font-awesome-icon
              :icon="['fa', 'sign-out']"
              class="sign-out"
              @click="logout()"
            />
          </div>
          <div
            data-toggle="modal"
            data-target="#loginModal"
            type="button"
            class="content_wrapper__right__icons__absolute__form_wrapper"
            v-if="!this.loggedUser"
          >
            <img
              src="../../public/assets/icons/login_unlogged.svg"
              alt="Login icon"
            />
          </div>

          <Cart />
          <font-awesome-icon
            :icon="['fa', 'bars']"
            size="3x"
            v-on:click="menuHidden = !menuHidden"
            :class="{
              showX: menuHidden,
            }"
          />
          <font-awesome-icon
            :icon="['fas', 'xmark']"
            size="3x"
            v-on:click="menuHidden = !menuHidden"
            :class="{
              showX: !menuHidden,
            }"
          />
        </div>
      </div>
      <div class="content_wrapper__middle">
        <a href="/">
          <img src="../../public/assets/logo.png" alt="Fitmo logo" />
        </a>
        <div class="content_wrapper__middle__search_wrapper">
          <div class="search" ref="searchContainer">
            <div class="search__wrapper">
              <input
                v-model="search"
                type="search"
                name="search"
                @focus="
                  () => {
                    searchProducts(true);
                    showSearchResults = true;
                  }
                "
                id="search"
                @blur="hideWithDelay"
              />
              <label for="search">
                <img
                  src="../../public/assets/icons/search.svg"
                  alt="Search bar"
                />
              </label>
            </div>
            <div class="search__items" v-if="showSearchResults">
              <div class="search__items__header"></div>
              <div class="search__items__wrapper">
                <div class="col-3-xs search__items__wrapper__categories">
                  <div class="col-12-xs">
                    <h4>Kategorie</h4>
                    <ul>
                      <li
                        v-for="category in searchResults.categories"
                        :key="category.id"
                      >
                        <router-link :to="'/kategorie/' + category.path">
                          {{ category.name }}
                        </router-link>
                      </li>
                    </ul>
                  </div>
                  <!--                  <div class="col-7-xs"></div>-->
                </div>
                <div class="col-9-xs search__items__wrapper__products">
                  <div class="search__items__wrapper__products__header">
                    <h4>Produkty</h4>
                  </div>
                  <div
                    class="search__items__wrapper__products__items home__eshop__wrapper"
                  >
                    <Product
                      v-for="product in searchResults?.products?.slice(0, 6)"
                      sizes="col-4-xs "
                      :products="product"
                      :key="product.id"
                    />
                  </div>
                  <div class="my-row">
                    <button
                      class="btn-yellow"
                      v-if="
                        this.search &&
                        searchResults?.products?.length > 6 &&
                        this.phraseToSearch !== 'byPopular'
                      "
                      @click.native="showSearchResults = false"
                    >
                      <router-link :to="`/vysledek-hledani/${this.search}`"
                        >Zobrazit vše
                      </router-link>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="content_wrapper__right">
            <div class="content_wrapper__right__icons">
              <div class="content_wrapper__right__icons__wrapper">
                <img
                  src="../../public/assets/icons/phone.svg"
                  alt="Phone icon"
                />
                <div class="content_wrapper__right__icons__wrapper__text">
                  <span>+420 702 064 265</span>
                  <span
                    class="content_wrapper__right__icons__wrapper__text__white"
                    >Kontaktujte nás</span
                  >
                </div>
              </div>
              <Cart />
            </div>
          </div>
        </div>
      </div>
      <div class="content_wrapper__footer">
        <img
          src="../../public/assets/banner.png"
          alt=""
          class="col-12-xs col-8-lg"
        />

        <nav
          :class="{
            showMenu: !menuHidden,
          }"
        >
          <ul>
            <li
              v-for="link in categories"
              :key="link.id"
              v-on:click="link.visible = !link.visible"
            >
              <div class="wrapper">
                <router-link
                  :to="{
                    name: 'Category',
                    params: { categoryname: link.url_path },
                  }"
                >
                  <font-awesome-icon
                    :icon="['fa', 'angle-down']"
                    :class="{ rotate: link.visible }"
                  />
                  <h2>{{ link.name }}</h2>
                </router-link>
              </div>
              <ul :class="{ visible: link.visible }">
                <router-link
                  class="btn-gray"
                  v-on:click="menuHidden = !menuHidden"
                  :to="{
                    name: 'Category',
                    params: { categoryname: link.url_path },
                  }"
                >
                  Všechny produkty
                </router-link>
                <li v-for="child in link.children" :key="child.key">
                  <router-link
                    v-on:click="menuHidden = !menuHidden"
                    :to="{
                      name: 'Category',
                      params: { categoryname: child.url_path.split('/') },
                    }"
                  >
                    <img :src="this.imageBasePath + child.image_path" alt="" />
                    <span>{{ child.name }}</span>
                  </router-link>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
</template>

<script>
import axios from "../api";
import Cart from "../components/Cart.vue";
import Product from "../components/Product.vue";

document.addEventListener("scroll", () => {
  const footer = document.querySelector(".content_wrapper__footer");
  const category = document.querySelector(".category");
  const scrollThreshold = 176; // Adjust this value as needed

  if (category && footer) {
    if (window.scrollY > scrollThreshold) {
      footer?.classList.add("fixed");
      category?.classList.add("fixed");
    } else {
      footer?.classList.remove("fixed");
      category?.classList.remove("fixed");
    }
  }
});

export default {
  components: {
    Cart,
    Product,
  },
  data() {
    return {
      showSearchResults: false,
      menuHidden: true,
      showX: false,
      categories: [],
      priceListHidden: true,
      activePrice: "CZK",
      searchResults: [],
      imageBasePath: `https://be.fitmo.cz/categories/`,
      search: "",
      phraseToSearch: "",
      credentials: {
        email: "",
        password: "",
      },
      loginError: "",
      loginQuote: "",
      loggedUser: null,
    };
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
  methods: {
    handleClickOutside(event) {
      // Close search only if click is OUTSIDE the `.search` container
      if (
        this.$refs.searchContainer &&
        !this.$refs.searchContainer.contains(event.target)
      ) {
        this.showSearchResults = false;
      }
    },

    async getCategories() {
      try {
        const response = await axios.post("/api/categories", {
          filter: [{ column: "id_parent", operator: "!=", value: null }],
        });
        this.categories = this.groupByKeyReduce(
          response.data.map((item) => {
            item.visible = false;
            return item;
          })
        );
      } catch (error) {
        console.log(error);
      }
    },

    async searchProducts(onFocus = false) {
      this.phraseToSearch = this.search;
      if (this.search === "" && !onFocus) {
        this.phraseToSearch = "byPopular";
      }
      if (onFocus) {
        if (this.search !== "" && this.searchResults.products.length > 0) {
          return;
        } else {
          this.phraseToSearch = "byPopular";
        }
      }
      try {
        const response = await axios.get(`/api/search/${this.phraseToSearch}`);
        if (this.search === "" && !onFocus) {
          this.searchResults = [];
        }
        this.searchResults = response.data;
      } catch (error) {
        console.error("Error fetching search results:", error);
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

    logout() {
      sessionStorage.removeItem("user");
      this.$store.commit("updateUser", null);
      this.loggedUser = null;
    },

    getLoggedUser() {
      this.loggedUser = JSON.parse(sessionStorage.getItem("user"));
    },

    login(credentials) {
      this.loginError = "";
      this.loginQuote = "Přihlašuji";

      axios
        .post("/api/login", { credentials })
        .then((response) => {
          this.loginQuote = "";
          sessionStorage.setItem("user", JSON.stringify(response.data));
          this.$store.commit("updateUser", response.data);
          this.loggedUser = response.data;
          const modalElement = document.getElementById("loginModal");
          modalElement.classList.remove("show");
          // wait 200 ms to hide the modal
          setTimeout(() => {
            $(".modal-backdrop").hide();
          }, 300);
        })
        .catch((error) => {
          if (error.response?.status === 401) {
            this.loginQuote = "";
            this.loginError = "Nesprávné přihlašovací údaje";
          }
          if (error.response?.status === 403) {
            this.loginQuote = "";
            this.loginError = "Ověřte si prosím svůj účet";
          }
          console.log(error);
        });
    },
  },

  watch: {
    search: function () {
      if (this.debouncedSearch) {
        clearTimeout(this.debouncedSearch);
      }
      this.debouncedSearch = setTimeout(() => {
        this.searchProducts();
      }, 500); // Adjust the debounce time as needed (e.g., 500 milliseconds)
    },
    $route(to, from) {
      // Hide search results when the route changes
      this.showSearchResults = false;
    },
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
    this.getCategories();
    this.getLoggedUser();
  },
};
</script>
<style lang="scss">
.modal-header {
  justify-content: space-between;

  button {
    margin: unset;
  }
}

header .loginModal {
  &.modal {
    border-radius: 2rem;
  }

  .modal-content {
    border-radius: 2rem;
  }

  .registration {
    position: relative;

    .close {
      position: absolute;
      background: transparent;
      top: 2rem;
      right: 2rem;
      transition: transform 0.3s ease;

      &:hover {
        transform: scale(1.1);
      }
    }

    & > div:last-child {
      padding: 0;
    }

    &__info {
      margin: unset;
      padding: 4rem;
      border-radius: 0 2rem 2rem 0;
    }

    .btn-black {
      width: 100%;
      text-decoration: none;
      font-weight: 100;
      font-size: 1.6rem;
      white-space: nowrap;
      padding: 0.75rem 4rem;
    }
  }

  .no-acc {
    margin-top: 3rem;
    margin-bottom: 1rem;

    span {
      font-size: 1.4rem;
      display: block;
      text-align: left;
      margin-bottom: 1rem;
    }

    a {
      text-decoration: none;
      text-align: center;
    }
  }

  .modal-body {
    padding: 0;
    flex-wrap: wrap;
  }
}

@media screen and (min-width: $screen-xl-min) {
  header .loginModal .modal-body {
    flex-wrap: nowrap;
  }

  header .loginModal .modal-dialog {
    max-width: fit-content !important;
  }

  .category.fixed {
    padding-top: 14rem !important;
  }

  .Category .content_wrapper__footer,
  .Search .content_wrapper__footer {
    transition: top 0.4s ease; // Add transition for the top property

    &.fixed {
      -webkit-box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.53);
      -moz-box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.53);
      box-shadow: 0 0 41px -4px rgba(0, 0, 0, 0.53);
      border-radius: 1rem;
      position: fixed;
      top: 1rem;
      left: 1rem;
      width: calc(100% - 2rem);
      z-index: 10;

      nav {
        background: $white;
        border-radius: 1rem;
        display: flex;

        ul li {
          div.wrapper a {
            padding: 0;
          }

          ul {
            top: 4rem;
          }
        }
      }
    }
  }
}

header {
  background: $yellow;
  position: relative;
  min-height: 14.2rem;

  form.login {
    background: $white;
    padding: 4rem;
    display: flex;
    flex-direction: column;
    min-width: 40rem;
    border-radius: 2rem;

    h2 {
      text-align: left;
      font-size: 2rem;
      width: 100%;
    }

    label {
      color: $black-second;
      margin-top: 1.2rem;

      &:first-child {
        margin: 0;
      }
    }

    input,
    .btn-black {
      border: 1px solid $gray-second;
      padding: 0.75rem 1rem;
      font-weight: 100;
    }

    input[type="text"],
    input[type="password"] {
      font-size: 1.4rem;
    }

    .btn-blue,
    .btn-black {
      width: 100%;
      text-decoration: none;
      font-weight: 100;
      font-size: 1.6rem;
      white-space: nowrap;
      padding: 0.75rem 4rem;
    }

    .btn-yellow {
      font-size: 1.7rem;
      padding: 0.75rem 4rem;
    }

    .btn-yellow,
    .btn-blue {
      line-height: 2.6rem;
      margin: 0;
    }

    input[type="password"] {
      margin-bottom: 2rem;
    }

    span {
      margin: 1rem auto;
    }

    a {
      text-decoration: underline;
      margin: auto;
      margin-top: 1rem;
      font-weight: 500;
    }
  }

  .content_wrapper {
    display: flex;
    margin: auto;
    position: relative;
    min-height: inherit;
    flex-wrap: wrap;

    &__upper {
      &__navigation-links {
        display: flex;

        li {
          padding: 0 2rem;
          position: relative;

          &:first-child {
            padding-left: 0;
          }

          &::after {
            content: "";
            position: absolute;
            width: 1px;
            height: 50%;
            top: 25%;
            background: $black;
            right: 0;
          }

          &:last-child::after {
            display: none;
          }
        }
      }
    }

    &__upper {
      & > a {
        margin-left: 5%;

        > img {
          height: 4rem;
          object-fit: contain;
          margin: 1rem 0;
        }
      }
    }

    &__middle {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      padding: 1rem 0;

      &__search_wrapper {
        position: relative;
        width: auto;
        display: flex;
        align-items: center;
        padding-left: 7%;
      }

      & > a {
        margin-left: 5%;

        > img {
          height: 5.5rem;
          object-fit: contain;
          margin: 1rem 0;
        }
      }

      .search {
        /* position: relative; */
        margin: 0;
        padding: 0 20px;
        background: transparent;
        z-index: 999;
        //position: relative;

        &__wrapper {
          z-index: 10;
          width: 50%;
          z-index: 4;
          position: relative;
        }

        &:focus-within .search__items {
          display: flex;
        }

        &__items {
          position: absolute;
          background: #fff;
          top: -2rem;
          left: 3rem;
          right: 2rem;
          z-index: 3;
          /* margin-right: 20px; */
          -webkit-box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
          -moz-box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
          box-shadow: 2px 3px 18px rgba(0, 0, 0, 0.29);
          border-radius: 2rem;
          overflow: hidden;
          flex-direction: column;

          &__header {
            width: 100%;
            min-height: 8rem;
            height: 8rem;
            background: white;
          }

          &__wrapper {
            width: 100%;
            height: 100%;
            display: flex;

            h4 {
              font-size: 16px;
              font-weight: 500;
            }

            & > div {
              height: 100%;
            }

            &__categories {
              display: flex;
              padding-left: 2rem;
            }

            &__products {
              padding-left: 1.5rem;
              position: relative;

              &::before {
                content: "";
                position: absolute;
                top: 5%;
                left: 0;
                width: 1px;
                height: 90%;
                background: $gray-second;
                z-index: 2;
              }

              .home__eshop__wrapper {
                &__name {
                }

                &__discounts {
                  span {
                    font-size: 1rem;
                    padding: 0.5rem 0.7rem;
                  }
                }
              }

              .my-row {
                margin-top: 1rem;

                .btn-yellow {
                  margin-bottom: 1rem;
                  padding: 0.5rem 2rem !important;
                }
              }

              &__items {
                display: flex;
                flex-wrap: wrap;

                & > div {
                  // height: auto;
                  padding: 1rem;

                  & > div {
                    //  height: 100%;
                  }

                  a {
                    // display: flex;
                    // flex-direction: column;
                    //                    height: 100%;
                    //                  justify-content: space-between;
                  }

                  h4 {
                    text-align: center;
                    line-height: 2rem;
                    font-weight: 600;
                    font-size: 1.8rem;
                  }

                  .home__eshop__wrapper__price {
                    margin-top: 0.6rem;
                  }

                  .home__eshop__wrapper__name {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                    width: 100%;
                    font-size: 1.4rem;
                  }

                  .home__eshop__wrapper {
                    &__price {
                      color: $black-second;
                      font-weight: 700;
                    }

                    &__img_wrapper {
                      img {
                        max-width: 24rem;
                        height: unset;
                      }
                    }
                  }
                }
              }
            }
          }
        }

        &:focus-within {
          .search__items {
            display: block;
          }
        }

        input {
          border: 0;
          height: 4rem;
          border-radius: 2rem;
          padding-left: 2rem;
          width: 100%;
          border: 1px solid $gray-second;
        }

        label {
          position: absolute;
          right: 0;
          top: 50%;
          transform: translateY(-50%);

          img {
            padding: 0.5rem;
            height: 3rem;
            border: none;
            margin: 0.6rem;
          }
        }
      }
    }

    &__right {
      display: flex;
      align-items: center;
      margin-left: auto;

      > span {
        border-right: 1px solid $black;
        padding: 0 1rem;
        margin-right: 1rem;
      }

      &__icons {
        display: flex;
        align-items: center;
        height: 100%;

        &__wrapper {
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;
          padding: 0;
          gap: 0.5rem;

          img {
            height: 3.5rem;
          }

          &__text {
            display: flex;
            flex-direction: column;
            font-weight: 100;
            font-size: 1.5rem;
            text-align: right;
            margin-right: 0.6rem;

            span:first-child {
              line-height: 1.2rem;
              margin-bottom: 0.4rem;
            }

            &__white {
              line-height: 1.6rem;
              font-size: 1.4rem;
              color: $white;
              text-align: left;
            }
          }

          &__number {
            background: $white;
            position: absolute;
            top: -1rem;
            right: -1rem;
            border-radius: 50%;
            height: 2rem;
            width: 2rem;
            font-size: 1.2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 400;
          }
        }

        img {
          height: 3.5rem;
          margin: 0 0.1rem;
        }

        &__absolute {
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 1.5rem;

          & > :last-child {
            padding-left: 1.05rem;
            padding-right: calc(5vw + 0.9rem);
            color: $yellow;
            background: $white;
          }

          & > svg {
            color: $white;
            margin-left: 0.2rem;
            padding: 1rem 5vw 1rem 0.9rem;
            display: none;
            position: relative;
          }

          &__loggedUser {
            display: flex;
            align-items: center;

            span {
              white-space: nowrap;
              padding: 0 !important;
            }

            svg.sign-out {
              display: block;
              margin-left: 1rem;
            }
          }

          &__price_wrapper {
            & > span {
              cursor: pointer;
              padding: 0 1rem;
              margin-right: 0.4rem;
              position: relative;
              display: flex;
              width: 80px;
              align-items: center;
              justify-content: space-between;

              .active {
                svg {
                  background: $yellow;
                  color: $white;
                }
              }

              &::before {
                content: "";
                height: 1px;
                width: 60%;
                position: absolute;
                left: 20%;
                bottom: 0;
                background: $gray-second;
                display: none;
              }

              &::after {
                content: "";
                width: 1px;
                height: 50%;
                position: absolute;
                top: 25%;
                right: 0;
                background: $black;
              }

              img {
                max-height: 0.6rem;
                margin: 0 0.3rem 0 0.6rem;
                padding: 0;
              }

              svg {
                display: block;
                background: $gray-second;
                color: $gray-second;
                border-radius: 0.3rem;
              }

              ul {
                position: absolute;
                display: none;
                flex-direction: column;
                background: $white;
                left: 0;
                border-radius: 0 0 1rem 1rem;
                top: 100%;
                width: 100%;
                z-index: 99;
                padding-bottom: 0.5rem;

                li {
                  display: flex;
                  margin: 0.4rem 0;
                  font-size: 1.4rem;
                  align-items: center;
                  justify-content: center;
                }

                label {
                  width: 2.7rem;
                  margin-left: 0.3rem;
                }
              }
            }

            &:hover {
              span {
                background: $white;
                border-radius: 1rem 1rem 0 0;
                position: relative;
                height: 100%;

                img {
                  transform: rotate(180deg);
                }

                &::before {
                  display: block;
                }

                > ul {
                  display: flex;
                }
              }
            }
          }
        }
      }
    }

    &__footer {
      width: 100%;

      > img {
        display: none;
      }
    }
  }

  nav {
    z-index: 9;
    display: none;
    position: fixed;
    top: 6.2rem;
    background: $white;
    justify-content: center;
    width: 100%;

    & > ul {
      display: flex;
      justify-content: center;
      // flex-direction: column;
      align-items: flex-start;
      margin-top: 1rem;
      margin-bottom: 1rem;

      & > li {
        display: flex;
        font-size: 2.3rem;
        white-space: nowrap;
        width: 100%;
        font-weight: 700;
        flex-direction: column;

        .wrapper {
          display: flex;
          align-items: center;
          width: 100%;

          & > a {
            width: 100%;
            padding: 2rem 0;
            display: flex;
            justify-content: flex-start;
            z-index: 3;
            align-items: center;

            h2 {
              font-weight: 600;
              font-size: 2.3rem;
              color: #393636;
              text-transform: uppercase;
            }
          }
        }

        ul {
          display: none;
          flex-direction: column;
          width: 100%;
          position: relative;
          width: calc(100% - 1.5rem);
          margin-top: 1rem;

          li {
            font-weight: 100;
            font-size: 1.6rem;
            padding: 0.2rem 0;

            a {
              display: flex;
              align-items: center;
            }
          }
        }

        svg {
          margin-right: 1rem;
        }

        img {
          height: 100%;
          width: 6rem;
          margin-right: 1rem;

          &.rotate {
            transform: rotateX(180deg);
          }
        }
      }
    }
  }
}

header.Home .content_wrapper__footer {
  display: flex;
  justify-content: flex-end;

  & > img {
    max-width: 75rem;
  }
}

@media screen and (max-width: $screen-xl-min) {
  header .loginModal .modal-dialog {
    form.login {
      width: 100%;
    }

    .registration {
      width: 100%;
      max-width: 100%;
      border-radius: 0 0 2rem 2rem;
    }
  }

  header {
    max-height: 62px;
    min-height: 62px;

    &.Home .content_wrapper {
      &__middle .search,
      &__right__icons > :nth-child(1) {
        display: none;
      }

      &__footer {
        padding-top: 6.8rem;
        justify-content: center;

        > img {
          display: block;
          padding-left: 5%;
        }
      }
    }

    nav {
      &.rotate {
        transform: rotateX(180deg);
      }

      &.showMenu {
        display: flex;
        overflow-y: scroll;
        height: calc(100% - 6.2rem);
        align-items: flex-start;

        &::-webkit-scrollbar {
          width: 0px; /* Remove scrollbar space */
          background: transparent; /* Optional: just make scrollbar invisible */
        }
      }
    }
  }
}

@media screen and (min-width: $screen-xl-min) {
  header .content_wrapper {
    &__upper {
      & > a {
        display: none;
      }
    }
  }
  header.Home .content_wrapper {
    nav {
      width: 33%;
      background: $yellow;
      z-index: 1;

      ul {
        align-items: flex-start;
        flex-direction: column;
        width: 100%;

        li {
          background: $yellow;
          padding-top: 2rem;

          .wrapper {
            svg {
              display: none !important;
            }

            margin-right: auto;

            a {
              padding: 0;
            }

            h2 {
              font-weight: 600;
              color: $white;
              margin-right: auto;
            }
          }

          ul {
            display: none;
          }
        }
      }
    }
  }
}

header.Home .content_wrapper__footer > img {
  display: block;
}

@media screen and (max-width: $screen-md-min - 1px) {
  header {
    .content_wrapper {
      &__right__icons {
        > :nth-child(2) {
          display: none;
        }
      }

      &__middle {
        .search__wrapper {
          width: 100%;
        }
      }
    }

    nav ul {
      flex-direction: column;

      li {
        display: flex;
        position: relative;

        .wrapper h2 a {
          pointer-events: none;
        }

        ul {
          &.visible {
            display: flex;

            > a,
            li {
              margin-left: 3rem;

              img {
                width: 3rem;
              }
            }

            a.btn-gray {
              border-radius: 1rem;
              font-size: 1.3rem;
              margin-bottom: 1rem;
              text-align: left;
              font-weight: 100;
            }
          }
        }
      }
    }
  }
}

@media screen and (max-width: $screen-xl-min) {
  .showX {
    display: flex !important;
  }

  header {
    .content_wrapper {
      &__middle {
        display: none;
      }

      &__upper {
        background: $yellow;
        position: fixed;
        z-index: 8;
        width: 100%;
        top: 0;
        flex-wrap: wrap;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;

        &__navigation-links {
          display: none;
        }
      }
    }

    header nav > ul > li {
      ul {
        display: flex;
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  header.Home {
    nav {
      & > ul {
        justify-content: center;
      }
    }
  }
  header {
    nav {
      & > ul {
        img {
          display: none;
        }

        > li {
          padding-bottom: 1rem;
          width: unset;
          padding: 0 2rem;

          svg {
            display: none !important;
          }

          .wrapper {
            a {
              justify-content: flex-start;
              padding: 2rem 0;
            }

            h2 {
              font-size: 1.2rem !important;
            }
          }

          ul {
            display: flex;
            margin-top: 0;

            a.btn-gray {
              display: none;
            }

            li {
              a {
                font-size: 1.1rem;
              }
            }
          }
        }
      }
    }
  }
}

@media screen and (min-width: $screen-xl-min) {
  header {
    .content_wrapper {
      &__upper {
        display: flex;
        width: 90%;
        margin: auto;
        justify-content: space-between;

        &__navigation-links {
          display: flex;
          flex-direction: row;
          align-items: flex-start;
          justify-content: space-evenly;
          padding: 2rem 0;
        }
      }

      &__middle {
        &__search_wrapper {
          width: 100%;
        }

        & > img {
          padding: 0;
        }

        .search {
          width: 40%;
          padding: 0;
          max-width: 45rem;

          input {
            width: 100%;
          }

          &__wrapper {
            width: 100%;
          }
        }
      }

      &__right {
        margin-right: 5vw;

        &__icons {
          svg {
            display: none;
          }

          .cart {
            margin: 0 1rem 0 0.5rem;
            margin-left: 5rem;
          }

          &__absolute {
            display: flex;

            span {
              padding: 0 1.5rem;
              margin: 0;
            }

            &__form_wrapper {
              position: relative;
              display: flex;
              justify-content: center;
              align-items: center;

              > img {
                margin: 0;
                height: 3.5rem;
                display: block;
                margin-left: 0.3rem;
              }

              //&:hover {
              //  background: $white;
              //  border-radius: 0.8rem 0.8rem 0 0;
              //
              //  form.login {
              //    display: flex;
              //  }
              //}
            }

            .cart {
              display: none;
            }
          }

          &__wrapper {
            &:nth-child(2) {
              margin-left: 5rem;
            }
          }
        }
      }
    }

    nav {
      display: flex;
      width: 100%;
      position: relative;
      background: $gray-third;
      top: unset;

      & > ul {
        img {
          display: block;
        }

        & > li {
          margin: 0;

          svg {
            display: block !important;
          }

          ul {
            display: none;
            left: 0;
            width: 100%;
            padding: 2rem 0;
            position: absolute;
            background: $white;
            z-index: 2;
            border-radius: 0 0 2rem 2rem;
            top: 8.3rem;
            margin: 0;
            grid-template-columns: repeat(auto-fill, 30rem);
            grid-template-rows: auto;
            row-gap: 1rem;
            justify-items: stretch;
            align-items: stretch;

            a.btn-gray {
              display: none;
            }

            li {
              display: flex;
              height: 100%;
              align-items: center;
              font-size: 1.8rem;
              margin-left: 5rem;

              img {
                width: 7rem;
              }

              a {
                span {
                  font-size: 1.4rem;
                  font-weight: 400;
                  position: relative;

                  &::before {
                    content: "";
                    display: block;
                    position: absolute;
                    bottom: 2px;
                    left: 0%;
                    transition: 0.2s ease width;
                    height: 1px;
                    background: $gray-second;
                    width: 0%;
                  }
                }

                &:hover {
                  span::before {
                    width: 100%;
                  }
                }
              }
            }
          }

          &:hover {
            background: $white;
            border-radius: 2rem 2rem 0 0;

            ul {
              display: grid;
            }

            svg {
              transform: rotate(180deg);
            }
          }

          .wrapper {
            width: 100%;
            justify-content: center;

            a {
              justify-content: center;
              display: flex;
            }

            h2 {
              font-size: 2.1rem !important;
              font-weight: 600;
            }
          }
        }
      }
    }
  }
}

@media screen and (min-width: $screen-xl-min) {
  header {
    .content_wrapper {
      &__upper {
        &__navigation-links li {
          font-size: 1.6rem;
        }
      }

      &__middle,
      &__upper {
        > a > img {
          height: 6rem;
        }

        .search {
          &__wrapper {
            input {
              height: 5rem;
              border-radius: 3rem;
            }
          }
        }
      }

      &__right {
        img {
          height: 4.3rem;
        }

        &__icons__absolute__form_wrapper img {
          height: 4.3rem;
        }
      }
    }

    &.Home .content_wrapper {
      &__footer {
        padding: 2rem 0 7rem 0;

        > img {
          max-width: 60%;
        }

        nav {
          padding-left: 6rem;

          > ul {
            > li {
              padding-bottom: 1rem;

              svg {
                display: none !important;
              }

              .wrapper h2 {
                font-size: 2.6rem !important;
              }
            }
          }
        }
      }
    }
  }
}

@media screen and (min-width: $screen-xl-min) {
  header.Home .content_wrapper__footer nav > ul > li .wrapper h2 {
    font-size: 2.8rem !important;
  }
}

@media screen and (min-width: $screen-xxxl-min) {
  header nav > ul > li {
    .wrapper > a h2 {
      font-size: 2.4rem !important;
    }

    ul {
      padding: 2rem 0;

      li a {
        span {
          font-size: 1.5rem;
        }

        img {
          width: 8rem;
        }
      }
    }
  }
}
</style>
