const formatDateWithoutTimezone = (dateString) => {
    let date;

    if (/^\d{2}-\d{2}-\d{4}( \d{2}:\d{2}:\d{2})?$/.test(dateString)) {
        const [dayMonthYear, time] = dateString.split(' ');
        const [day, month, year] = dayMonthYear.split('-');
        date = new Date(`${year}-${month}-${day}${time ? `T${time}` : 'T00:00:00'}`);
    } else if (/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/.test(dateString)) {
        const [datePart, time] = dateString.split(' ');
        date = new Date(`${datePart}${time ? `T${time}` : 'T00:00:00'}`);
    } else {
        date = new Date(dateString);
    }

    if (isNaN(date.getTime())) {
        throw new Error('Invalid date format');
    }

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}
