//this is goofy
function GET_IMAGE(image) {
    if (!image) {
        return './media/notfound.jpg';
    } else {
        return `./media/${image}`;
    }
}