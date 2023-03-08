<template>
  <div class="registration row">
    <div class="col-12-xs col-6-md">
      <h1>Registrace</h1>
      <form action="">
        <label for="name">Jméno*</label>
        <input type="text" name="" id="name" />
        <label for="surname">Příjmení*</label>
        <input type="text" name="" id="surname" />
        <label for="email">Email*</label>
        <input type="text" name="" id="email" />
        <label for="password">Vytvořte heslo*</label>
        <input type="text" name="" id="password" />
        <span>Musí obsahovat minimálně 5 znaků</span>
        <label for="password">Potvrdit heslo*</label>
        <input type="text" name="" id="password" />
        <div class="options">
          <div class="row">
            <input type="checkbox" name="Agrees" id="first" />
            <label for="first">
              <font-awesome-icon :icon="['fa', 'check']" />Chci odebírat akční
              nabídky a novinky</label
            >
          </div>
          <div class="row">
            <input type="checkbox" name="Agrees" id="second" />
            <label for="second">
              <font-awesome-icon :icon="['fa', 'check']" />Souhlasím s
              obchodními podmínkami</label
            >
          </div>
          <div class="row">
            <input type="checkbox" name="Agrees" id="third" checked />
            <label for="third">
              <font-awesome-icon :icon="['fa', 'check']" />Souhlasím se
              zpracování osobních údajů</label
            >
          </div>
        </div>
        <input type="submit" value="Registrovat se" class="btn-yellow" />
      </form>
    </div>

    <div class="col-12-xs col-6-md">
      <div class="registration__info">
        <h2>Proč se registrovat?</h2>
        <article>
          <p>Ještě nemáš vytvořený účet?</p>
          <p>Zaregistruj se a získej spousty výhod</p>
        </article>
        <ul>
          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>Rychlejší nakupování</span>
          </li>
          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>Historie všech svých objednávek</span>
          </li>

          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span>Nemusíš vyplňovat své údaje</span>
          </li>
          <li>
            <img src="../../public/assets/icons/check_transparent.svg" alt="" />
            <span> FUBO novinky z první ruky</span>
          </li>
        </ul>
      </div>
      <div class="registration__buttons">
        <h2>Registrace přes sociální sítě</h2>
        <a class="btn-blue" href="#">Registrovat se přes Facebook</a>
        <a class="btn-google" href="#">Registrovat se přes Google</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {};
  },
};
</script>
<style lang="scss">
.registration {
  margin: auto;

  & > div:first-child {
    padding-left: 5%;
    padding-right: 5%;
  }
  & > div:last-child {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  h1 {
    text-align: left;
    margin-bottom: 1rem;
  }
  h2 {
    text-align: left;
    font-size: 2rem;
  }
  form {
    display: flex;
    flex-wrap: wrap;

    > label {
      width: 100%;
      font-size: 1.4rem;
      margin-top: 1.2rem;
    }
    > span {
      width: 100%;
      font-size: 1.1rem;
    }
    input[type="text"] {
      width: 100%;
      padding: 1rem;
    }
    input[type="checkbox"] {
      &:checked + label svg {
        background: $yellow;
        border-color: $yellow;
      }
    }
    .options {
      margin: 1.5rem 0 2.5rem 0;
      width: 100%;

      label {
        display: flex;
        align-items: center;
        white-space: nowrap;
        font-size: 1.1rem;
        line-height: 2.2rem;

        svg {
          margin-right: 0.6rem;
        }
      }
      .row svg {
        border: 1px solid $gray-second;
        padding: 0.1rem;
        border-radius: 0.5rem;
        color: $white;
      }
    }
    input[type="submit"] {
      padding: 0.75rem 8rem;
      line-height: 2.6rem;
      font-size: 1.6rem;
      cursor: pointer;
    }
  }

  &__info {
    background: $gray-third;
    padding: 3.5rem 10% 4rem 10%;
    width: 100%;
    color: $black-headers;
    margin-top: 2rem;

    article {
      line-height: 2rem;
      margin: 1rem 0;
      p {
        font-size: 1.5rem;
      }
    }

    ul {
      margin: 3rem 0 2rem 0;
      li {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        margin-bottom: 1.7rem;

        span {
          line-height: 1.8rem;
        }

        img {
          height: 1.7rem;
          margin-right: 1.5rem;
        }
      }
    }
  }
  &__buttons {
    width: 80%;
    margin: 2rem 0 0 0;
    max-width: 38rem;

    h2 {
      font-size: 1.6rem;
      font-weight: 600;
    }
  }
  .btn-blue,
  .btn-google {
    min-width: 90%;
    width: 90%;
    text-decoration: none;
    font-weight: 100;
    font-size: 1.6rem;
    white-space: nowrap;
    padding: 0.75rem;
    line-height: 2.6rem;
    margin: 1rem 0;
  }
  .btn-blue {
    margin-top: 0.5rem;
  }
  .btn-google {
    margin-bottom: 0;
  }
}
@media screen and(max-width: $screen-sm-min - 1px) {
  .registration {
    form input[type="submit"],
    &__buttons .btn-blue,
    &__buttons .btn-google {
      width: 100%;
    }
  }
}
@media screen and(min-width: $screen-md-min) {
  .registration {
    margin-bottom: 7rem;
    &__info {
      border-radius: 1.5rem;
      max-width: 45rem;
    }
    & > div:last-child {
      padding-right: 5%;
    }
  }
}
@media screen and(max-width: $screen-md-min - 1px) {
  .registration {
    input[type="submit"] {
      margin-bottom: 2.5rem;
    }
    & > div:last-child {
      display: flex;
      flex-direction: column-reverse;
    }

    &__buttons {
      width: 80%;
      margin: 0 0 3.3rem 10%;
    }
  }
}

@media screen and(min-width: $screen-lg-min) {
  .registration {
    &__info {
    }
  }
}
</style>