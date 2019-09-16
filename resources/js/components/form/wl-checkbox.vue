<template>
    <div class="checkbox-container">
        <input
            type="checkbox"
            :name="name"
            :value="value"
            :id="id"
            @change="$emit('change')"
        />
        <label :for="id" class="label">{{ label }}</label>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true
        },
        name: {
            type: String,
            default: null
        },
        value: {
            required: true
        },
        label: {
            type: String,
            default: ""
        }
    }
}
</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

.checkbox-container {
    display: inline-block;
    margin-right: 10px;
}

input[type="checkbox"] {
    display: none;
    // checkboxを非表示にする
}

.label {
    position: relative;
    padding: 0 0 0 25px;
    // ボックス内側の余白を指定する
}

.label:hover:after {
    border-color: $brand-color-primary;
    // ボックスの境界線を実線で指定する
}

.label:after,
.label:before {
    position: absolute;
    content: "";
    display: block;
    top: 50%;
}

.label:after {
    left: 0px;
    margin-top: -10px;
    width: 15px;
    height: 15px;
    border: 2px solid lighten($brand-color-primary, 30%);
    border-radius: 6px;
}

.label:before {
    left: 6px;
    margin-top: -7px;
    width: 5px;
    height: 9px;
    border-right: 3px solid $brand-color-primary;
    border-bottom: 3px solid $brand-color-primary;
    transform: rotate(45deg);
    opacity: 0;
}

input[type="checkbox"]:checked + .label:before {
    opacity: 1;
}
</style>
