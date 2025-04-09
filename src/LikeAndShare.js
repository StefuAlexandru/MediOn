function handleClick(id){
    const likes = document.getElementById(id);
    const likeButton = document.getElementById('like-button');
    if(id === 'likes-count'){
        likeButton.style.color= "#beef2b";
        document.cookie = "isLike=true";
        likeButton.style.pointerEvents= "none";
    }

    let numberOfLikes = parseInt(likes.textContent);
    numberOfLikes++;
    likes.textContent = numberOfLikes;
}
