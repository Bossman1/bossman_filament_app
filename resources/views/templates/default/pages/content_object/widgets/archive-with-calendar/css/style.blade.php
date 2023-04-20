<style>
.calendar {
    position: relative;
    width: 241px;
    background-color: #fff;
    box-sizing: border-box;
    box-shadow: 0 5px 50px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    overflow: hidden;
}

.calendar__picture {
    position: relative;
    height: 100px;
    padding: 20px;
    color: #fff;
    background: #262626 url("/images/widgets/pic.jpg") no-repeat center/cover;
    text-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
}
.calendar__picture::before {
    content: "";
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    position: absolute;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.1));
}
.calendar__picture h2 {
    margin: 0;
    color: white;
font-size: 19px;
}
.calendar__picture h3 {
    margin: 0;
    font-weight: 500;
    color: white;
    font-size: 18px;
}

.calendar__date {
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(25px, 1fr));
    grid-gap: 4px;
    box-sizing: border-box;
    font-size: 13px;
}

.calendar__day {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 25px;
    font-weight: 600;
    color: #262626;
}
.calendar__day:nth-child(7) {
    color: #ff685d;
}

.calendar__number {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 25px;
    color: #262626;
}
.calendar__number:nth-child(7n) {
    color: #ff685d;
    font-weight: 700;
}
.calendar__number--current, .calendar__number:hover {
    background-color: #009688;
    color: #fff !important;
    font-weight: 700;
    cursor: pointer;
}



.calendar__number--records, .calendar__number:hover {
    background-color: #ffb600;
    color: #fff !important;
    font-weight: 700;
    cursor: pointer;
}
</style>
