export default class {
    static getFormatted(totalMinutes) {
        let hours = Math.floor(totalMinutes / 60)
        let minutes = totalMinutes - (hours * 60)

        hours = hours < 9 ? `0${hours}` : hours.toString()
        minutes = minutes < 9 ? `0${minutes}` : minutes.toString()

        return `${hours}:${minutes}`
    }
}