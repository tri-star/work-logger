global.expect = require("expect")
global.Vue = require("vue")
global.bus = new Vue()

import { shallow, mount } from "vue-test-utils"
import expect from "expect"
import TreeMenu from "../../../resources/js/components/Common/TreeMenu.vue"

describe("TreeMenu", () => {
    it("renders the correct title on the page", () => {
        let wrapper = shallow(TreeMenu)
        wrapper.setProps({
            label: "test label",
            nodes: [
                {
                    label: "sub menu",
                    nodes: [
                        {
                            label: "deep menu",
                            click() {
                                alert(1)
                            }
                        }
                    ]
                }
            ]
        })

        expect(wrapper.html()).toContain("deep menu")
    })
})
