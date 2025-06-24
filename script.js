const productGrid = document.getElementById('productGrid');

const sampleProducts = [
    {
        id: 1,
        name: "Macrame Keychain",
        price: "500",
        img: "https://via.placeholder.com/300x200"
    },
    {
        id: 2,
        name: "Hand-painted Mug",
        price: "900",
        img: "https://via.placeholder.com/300x200"
    },
    {
        id: 3,
        name: "Custom Tote Bag",
        price: "1200",
        img: "https://via.placeholder.com/300x200"
    }
];

sampleProducts.forEach(product => {
    const div = document.createElement('div');
    div.className = 'product-card';
    div.innerHTML = `
    <img src="${product.img}" alt="${product.name}" />
    <h3>${product.name}</h3>
    <p>Rs. ${product.price}</p>
    <button>View</button>
  `;
    productGrid.appendChild(div);
});
