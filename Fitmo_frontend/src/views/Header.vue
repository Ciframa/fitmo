<template>
  <header :class="$route.name">
    <div class="content_wrapper">
      <div class="content_wrapper__upper">
        <ul>
          <!--<li>O NÁS</li>-->
          <li>
            <router-link to="/info/doprava">DOPRAVA A PLATBA</router-link>
          </li>
          <!--<li>REFERENCE</li>-->
          <li><router-link to="/info/kontakt">KONTAKT</router-link></li>
        </ul>
      </div>
      <div class="content_wrapper__middle">
        <a href="/">
          <img src="../../public/assets/logo.png" alt="Fitmo logo" />
        </a>
        <div class="search">
          <div class="search__wrapper">
            <input type="search" name="search" id="search" />
            <label for="search">
              <img
                src="../../public/assets/icons/search.svg"
                alt="Search bar"
              />
            </label>
          </div>
          <div class="search__items"></div>
        </div>
        <div class="content_wrapper__right">
          <div class="content_wrapper__right__icons">
            <div class="content_wrapper__right__icons__absolute">
              <div
                class="content_wrapper__right__icons__absolute__price_wrapper"
                :class="{
                  content_wrapper__right__icons__absolute__price_wrapper__clicked:
                    !priceListHidden,
                }"
                @click="priceListHidden = !priceListHidden"
              >
                <span>
                  CZK
                  <img
                    src="../../public/assets/icons/path_down.svg"
                    alt="Path down"
                  />
                  <ul>
                    <li>
                      <font-awesome-icon :icon="['fa', 'check']" />
                      <label>CZK</label>
                    </li>
                    <li>
                      <font-awesome-icon :icon="['fa', 'check']" />
                      <label>EUR</label>
                    </li>
                  </ul>
                </span>
              </div>
              <div
                class="content_wrapper__right__icons__absolute__form_wrapper"
              >
                <img
                  src="../../public/assets/icons/login.svg"
                  alt="Login icon"
                />
                <form action="hihih">
                  <label for="email">E-mail</label>
                  <input type="text" id="email" class="btn" />

                  <label for="password">Heslo</label>
                  <input type="password" id="password" class="btn" />

                  <input
                    class="btn-yellow"
                    type="submit"
                    value="Přihlásit se"
                  /><span>Nebo</span>
                  <a class="btn-blue" href="#">Přihlásit se přes Facebook</a>
                  <a href="#">Zapomenuté heslo</a>
                  <div class="no-acc">
                    <span href="#">Ještě nemáš účet?</span>
                    <a href="/registrace" class="btn-black"
                      >Chci se zaregistrovat</a
                    >
                  </div>
                </form>
              </div>
            </div>
            <div class="content_wrapper__right__icons__wrapper">
              <div class="content_wrapper__right__icons__wrapper__text">
                <span>+420 702 064 265</span>
                <span
                  class="content_wrapper__right__icons__wrapper__text__white"
                  >Kontaktujte nás</span
                >
              </div>
              <img src="../../public/assets/icons/phone.svg" alt="Phone icon" />
            </div>
            <a
              href="/objednavka/kosik"
              class="content_wrapper__right__icons__wrapper"
            >
              <span class="content_wrapper__right__icons__wrapper__text__white"
                >Košík je<br />prázdný</span
              >
              <img src="../../public/assets/icons/cart.svg" alt="Cart icon" />
              <span class="content_wrapper__right__icons__wrapper__number"
                >1</span
              >
            </a>

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
                <img
                  src="../../public/assets/icons/path_down.svg"
                  alt="Path
                down"
                  :class="{ rotate: link.visible }"
                />
                <h2>
                  <router-link
                    :to="{
                      name: 'Category',
                      params: { categoryname: link.url_path },
                    }"
                  >
                    {{ link.name }}
                  </router-link>
                </h2>
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
                    <img
                      src="../../public/assets/products/main/ocr_vybaveni.png"
                      alt=""
                    />
                    {{ child.name }}
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

export default {
  components: {},
  data() {
    return {
      menuHidden: true,
      showX: false,
      categories: [],
      priceListHidden: true,
    };
  },

  methods: {
    getCategories() {
      axios
        .get("/api/categories")
        .then((response) => {
          this.categories = this.groupByKeyReduce(
            response.data.map((item) => {
              item.visible = false;
              return item;
            })
          );
        })
        .catch((error) => {
          console.log(error);
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
  },

  mounted() {
    this.getCategories();
  },
};
</script>
<style lang="scss">
header {
  background: $yellow;
  position: relative;
  min-height: 14.2rem;

  .content_wrapper {
    display: flex;
    margin: auto;
    position: relative;
    min-height: inherit;
    flex-wrap: wrap;

    &__upper {
      display: none;
      padding: 2rem 0;

      ul {
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

    &__middle {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;

      & > a {
        margin-left: 5%;

        > img {
          height: 4rem;
          margin: 1rem 0;
        }
      }

      .search {
        height: 100%;
        width: 100%;
        padding: 2rem 0;
        margin: auto;
        align-items: center;
        background: $yellow;
        display: flex;
        position: relative;
        align-items: center;

        &__wrapper {
          z-index: 10;
          width: 50%;
          z-index: 4;
          position: relative;
        }

        &__items {
          position: absolute;
          height: 37rem;
          background: #fff;
          left: -1rem;
          top: -1rem;
          display: none;
          z-index: 2;
          width: 100vw;
          border: 3px solid black;
          z-index: 5;
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

      > span {
        border-right: 1px solid $black;
        padding: 0 1rem;
        margin-right: 1rem;
      }

      &__icons {
        display: flex;
        align-items: center;
        height: 100%;

        img {
          height: 3.5rem;
          margin: 0 0.1rem;
        }

        &__wrapper:nth-child(3) {
          margin: 0 1rem 0 0.5rem;
        }

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

        &__absolute {
          display: flex;
          justify-content: center;
          align-items: center;

          &__price_wrapper {
            & > span {
              cursor: pointer;
              padding: 0 1rem;
              margin-right: 0.4rem;
              position: relative;
              display: flex;
              align-items: center;

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
                background: $yellow;
                color: $white;
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
            &__clicked {
              span {
                background: $white;
                border-radius: 1rem 1rem 0 0;
                position: relative;

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

          form {
            background: $white;
            position: absolute;
            top: 100%;
            right: 0;
            padding: 2rem 3.3rem;
            display: none;
            flex-direction: column;
            z-index: 99;
            border-radius: 2rem 0 2rem 2rem;

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
            .no-acc {
              margin-top: 2rem;
              margin-bottom: 1rem;

              span {
                font-size: 1.6rem;
                display: block;
                text-align: center;
              }
              a {
                text-decoration: none;
                text-align: center;
              }
            }
          }
        }

        &__wrapper {
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;

          &__text {
            display: flex;
            flex-direction: column;
            font-weight: 100;
            font-size: 1.5rem;
            text-align: right;
            margin-right: 0.6rem;

            span:first-child() {
              line-height: 1.2rem;
              margin-bottom: 0.4rem;
            }

            &__white {
              line-height: 1.6rem;
              font-size: 1.4rem;
              color: $white;
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
    padding-bottom: 3rem;

    & > ul {
      display: flex;
      justify-content: center;
      flex-direction: column;
      align-items: center;
      margin-top: 2rem;

      & > li {
        display: flex;
        font-size: 2.3rem;
        white-space: nowrap;
        padding: 2rem 0 0 0;
        width: 100%;
        font-weight: 700;
        flex-direction: column;

        .wrapper {
          display: flex;
          align-items: center;
          margin-right: auto;

          h2 {
            font-weight: 700;
            font-size: 2.3rem;
            color: $black-second;
            text-transform: uppercase;
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
        img {
          height: 100%;
          width: 2rem;
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
@media screen and(max-width: $screen-lg-min - 1px) {
  header {
    &.Home .content_wrapper {
      &__middle .search,
      &__right__icons > :nth-child(2) {
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
@media screen and(min-width: $screen-lg-min) {
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
          .wrapper {
            img {
              display: none;
            }
            margin-right: auto;
            h2 {
              margin-right: auto;
              a {
                color: $white;
              }
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

@media screen and(max-width: $screen-md-min - 1px) {
  header {
    .content_wrapper {
      &__right__icons {
        > :nth-child(2) {
          display: none;
        }
      }
      &__middle .search__wrapper {
        width: 100%;
      }
    }
    nav ul li {
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
@media screen and(max-width: $screen-lg-min - 1px) {
  .showX {
    display: flex !important;
  }

  header {
    .content_wrapper {
      &__right__icons {
        &__wrapper {
          margin-right: 0.3rem;
        }
        > :nth-child(2) {
          position: absolute;
          bottom: 0;
          right: 5vw;
          transform: translateY(-50%);
        }

        & > :nth-child(3) {
          span:first-child {
            display: none;
          }
        }
      }
      &__middle {
        background: $yellow;
        position: fixed;
        z-index: 8;
        width: 100%;
        top: 0;
        flex-wrap: wrap;
        .search {
          order: 5;
          margin: 0 5%;
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
@media screen and(min-width: $screen-sm-min) {
}
@media screen and(min-width: $screen-md-min) {
  header {
    nav {
      & > ul {
        flex-direction: row;
        align-items: flex-start;
        width: 90%;

        img {
          display: none;
        }

        > li {
          .wrapper h2 {
            font-size: 1.2rem;
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
    .content_wrapper__middle .search {
    }
  }
}

@media screen and(min-width: $screen-lg-min) {
  header {
    .content_wrapper {
      &__upper {
        display: flex;
        width: 90%;
        margin: auto;
      }

      &__middle {
        padding: 1rem 0 3rem 0;
        & > img {
          padding: 0;
        }
        .search {
          width: 30%;
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
        margin-right: 5%;

        &__icons {
          svg {
            display: none;
          }

          &__absolute {
            position: absolute;

            right: 5%;
            top: 0;
            padding-top: 1.75rem;
            span {
              padding: 0 1.5rem;
              margin: 0;
            }

            &__form_wrapper {
              > img {
                margin: 0;
                padding-left: 1.5rem;
              }

              &:hover {
                background: $white;
                border-radius: 0.8rem 0.8rem 0 0;

                form {
                  display: flex;
                }
              }
            }
          }

          &__wrapper {
            &:nth-child(3) {
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

          ul {
            display: none;
            left: 5%;
            width: 90%;
            padding: 2rem 0;
            position: absolute;
            background: $white;
            z-index: 2;
            border-radius: 0 0 2rem 2rem;
            top: 7.3rem;
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
              height: 4rem;
              align-items: center;
              font-size: 1.8rem;
              margin-left: 5rem;
            }
          }
          &:hover {
            background: $white;
            border-radius: 2rem 2rem 0 0;
            ul {
              display: grid;
            }
          }
          .wrapper {
            width: 100%;
            justify-content: center;

            h2 {
              font-size: 2.1rem;
              font-weight: 600;
            }
          }
        }
      }
    }
  }
}

@media screen and(min-width: $screen-xl-min) {
  header {
    .content_wrapper {
      &__upper {
        ul li {
          font-size: 1.6rem;
        }
      }
      &__middle {
        > a > img {
          height: 6rem;
        }
        .search {
          &__wrapper {
            margin-top: 1.1rem;
            input {
              height: 5rem;
              border-radius: 3rem;
            }
          }
        }
      }
      &__right {
        margin-top: 1.1rem;
        &__icons img {
          height: 4.3rem;
        }
        &__icons__absolute {
          padding-top: 1.25rem;
          span {
            font-size: 1.6rem;
          }
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
              .wrapper h2 {
                font-size: 2.6rem;
              }
            }
          }
        }
      }
    }
  }
}
</style>
