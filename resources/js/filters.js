import dayjs from "dayjs"

export default {
    format_date(value, format = "", empty = "", error = "---") {
        if (String(value).trim() === "") {
            return empty
        }
        if (!dayjs(value).isValid()) {
            return error
        }

        return dayjs(value).format(format)
    }
}
