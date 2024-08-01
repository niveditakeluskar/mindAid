<script>
import axios from "axios";

//ajax-submit functions
export const onAjaxErrors = (form, fields, response) => {
  const errors = response.data.errors;
  const fieldNames = Object.keys(errors);
  for (let i = 0; i < fieldNames.length; i++) {
    try {
      const field = fields.fields[fieldNames[i]];
      if (!field) return;
      if (field && field.dataset.feedback) {
        field.querySelector(
          `[data-feedback-area="${field.dataset.feedback}"]`
        ).innerHTML = errors[fieldNames[i]];
      } else {
        if (field.nextElementSibling.classList.contains("invalid-feedback")) {
          field.nextElementSibling.innerHTML = errors[fieldNames[i]];
        } else {
          field.closest(".forms-element").nextElementSibling.innerHTML =
            errors[fieldNames[i]];
          field.closest(".forms-element").nextElementSibling.style.display =
            "block";
        }
      }
      field.classList.add("is-invalid");
    } catch (e) {
      console.error(`Ajax error reporting: for field ${fieldNames[i]}`, e);
    }
  }
};

export const getFields = (formName) => {
  const form = document.querySelector(`form[name="${formName}"]`);
  const result = {
    fields: {},
    values: {},
  };
  form.querySelectorAll("input, select, textarea").forEach((element) => {
    const type = element.getAttribute("type");
    const name = element.getAttribute("name");
    if (name) {
      const nameParts = name.split(/\[\s*\]|\[([^\]]*)\]/);
      let key = nameParts[0];
      let field = nameParts[0];
      let scope = result.values;
      for (let i = 1; i < nameParts.length; i += 2) {
        if (!nameParts[i]) {
          if (!Array.isArray(scope[key])) scope[key] = [];
          scope = scope[key];
          key = scope.length;
        } else {
          if (typeof scope[key] !== "object" || Array.isArray(scope[key]))
            scope[key] = {};
          scope = scope[key];
          key = nameParts[i];
        }
        field += `.${key}`;
      }
      if (type === "checkbox") {
        scope[key] = element.checked;
      } else if (type === "radio") {
        if (!element.checked && scope[key] === undefined) scope[key] = null;
        else if (element.checked) scope[key] = element.value;
      } else scope[key] = element.value;
      result.fields[field] = element;
    }
  });
  return result;
};

export const ajaxSubmit = (formName, onResult, onErrors) => {
  const form = document.querySelector(`form[name="${formName}"]`);
  const action = form.getAttribute("action") || window.location.href;
  const method = (form.getAttribute("method") || "GET").toUpperCase();
  const enabledFields = form.querySelectorAll(
    "input:enabled, select:enabled, button:enabled"
  );
  form
    .querySelectorAll(".is-invalid")
    .forEach((element) => element.classList.remove("is-invalid"));
  form
    .querySelectorAll(".invalid-feedback")
    .forEach((element) => (element.innerHTML = ""));
  enabledFields.forEach((field) => (field.disabled = true));
  const fields = getFields(formName);
  let data, params;
  if (method === "GET") {
    data = undefined;
    params = fields.values;
  } else {
    data = fields.values;
    params = undefined;
  }

  axios({
    method: method,
    url: action,
    data: data,
    params: params,
  })
    .then((response) => {
      enabledFields.forEach((field) => (field.disabled = false));
      setTimeout(() => {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = false;
        }
      }, 3000);
      if (!onResult || (onResult && onResult(form, fields, response))) {
        if (response.data && response.data.redirect) {
          window.location = response.data.redirect;
        }
      }
    })
    .catch((error) => {
      enabledFields.forEach((field) => (field.disabled = false));
      setTimeout(() => {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = false;
        }
      }, 3000);
      if (
        error.response &&
        (!onErrors || onErrors(form, fields, error.response))
      ) {
        onAjaxErrors(form, fields, error.response);
      }
      if (onResult) {
        onResult(form, fields, error.response, error);
      }
    });
};

export const ajaxForm = (name, onResult, onSubmit, onErrors) => {
  const form = document.querySelector(`form[name="${name}"]`);
  if (!form) {
    console.error(`Form with name '${name}' not found.`);
    return;
  }
  if (form._hasAjaxFormListener) {
    console.warn(`Ajax form listener already attached to form '${name}'.`);
    return;
  }
  form._hasAjaxFormListener = true;
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
      submitButton.disabled = true;
    }
    ajaxSubmit(name, onResult, onErrors);
  });
};

//population functions
export const populateForm = async (url, formName, patientId) => {
  if (!patientId) return;
  try {
    const response = await axios.get(url, {
      params: { patient_id: patientId },
    });
    const data = response.data;
    dynamicFormPopulate(formName, data);
  } catch (error) {
    console.error("Population Error:", error);
  }
};

export const dynamicFormPopulate = (formName, data) => {
  const form = document.querySelector(`form[name="${formName}"]`);
  if (!form) {
    return;
  }
  try {
    for (let key in data[formName]?.static) {
      let field = form.querySelector(`[name="${key}"]`);
      if (!field) {
        continue;
      }
      dynamicFormPopulateField(field, data[formName].static[key]);
    }
    document.querySelectorAll("[data-dynamic-area]").forEach((area) => {
      dynamicFormClear(area.dataset.dynamicArea);
    });

    // Populate dynamic fields
    for (const group in data[formName]?.dynamic) {
      if (Object.hasOwnProperty.call(data[formName].dynamic, group)) {
        for (const hash in data[formName].dynamic[group]) {
          if (Object.hasOwnProperty.call(data[formName].dynamic[group], hash)) {
            dynamicFormAdd(group, data[formName].dynamic[group][hash], hash);
          }
        }
      }
    }
  } catch (error) {
    console.error("Error in dynamicFormPopulate:", error);
  }
};

const dynamicFormPopulateRadio = (field, value) => {
  const fieldName = field.getAttribute("name");
  const radioInput = document.querySelector(
    `input[type="radio"][name="${fieldName}"][value="${value}"]`
  );
  if (fieldName === "oxygen" && value == 0) {
    const notesField = document.querySelector('textarea[name="notes"]');
    if (notesField) {
      notesField.closest(".col-md-12").style.display = "block";
    }
  }
  if (radioInput) {
    radioInput.dispatchEvent(new Event("change"));
  } else {
    console.warn(
      `No radio button found with name "${name}" and value "${value}"`
    );
  }
};

const dynamicFormPopulateCheckbox = (field, value) => {
  field.checked = value;
  field.dispatchEvent(new Event("change"));
};

const dynamicFormPopulateField = (field, value) => {
  const type = field.tagName.toLowerCase();
  if (type === "input") {
    const fieldType = field.getAttribute("type").toLowerCase();
    if (fieldType === "radio") {
      dynamicFormPopulateRadio(field, value);
    } else if (fieldType === "checkbox") {
      dynamicFormPopulateCheckbox(field, value);
    } else if (fieldType === "date" || fieldType === "datetime-local") {
      if (value) {
        const formattedDate = new Date(value).toISOString().split("T")[0];
        field.value = formattedDate;
        field.dispatchEvent(new Event("input"));
      }
    } else {
      field.value = value;
      field.dispatchEvent(new Event("input"));
    }
  } else if (type === "select") {
    field.value = value;
    field.dispatchEvent(new Event("change"));
  } else if (type === "textarea") {
    if (field.__vue__) {
      field.__vue__.value = value;
    } else {
      field.value = value;
      field.dispatchEvent(new Event("input"));
    }
  } else {
    console.log("Unhandled field type", type);
  }
};
</script>
<style>
textarea {
  min-height: 50px;
}
</style>
