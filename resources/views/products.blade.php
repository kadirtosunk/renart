<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ürün Carousel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body class="bg-gray-100 text-gray-900">
  <div class="container mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-6">Ürün Carousel</h1>

    <div class="swiper mySwiper">
      <div class="swiper-wrapper" id="product-list">
      </div>

      <div class="flex justify-between mt-4">
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <script>
    function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }
  </script>

  <script>
    axios.get('/products')
      .then(response => {
        const products = response.data;
        const container = document.getElementById('product-list');

        products.forEach((product, index) => {
          const colors = Object.keys(product.images);
          const defaultColor = colors[0];
          const rating = Math.round(product.rating);
          const imageId = `product-img-${index}`;
          const colorLabelId = `color-label-${index}`;

          const card = document.createElement('div');
          card.className = "swiper-slide bg-white rounded-lg shadow p-4";

          card.innerHTML = `
            <img id="${imageId}" src="${product.images[defaultColor]}" class="w-full h-64 object-cover rounded mb-4 transition-all duration-300">
            <h2 class="text-lg font-semibold">${product.name}</h2>
            <p class="text-gray-600 mt-1">$${product.price} USD</p>

            <!-- Yıldızlar ve skor -->
            <div class="flex items-center gap-2 mt-2 mb-1">
              <div class="flex">
                ${[...Array(5)].map((_, i) => `
                  <svg class="w-4 h-4 ${i < rating ? 'fill-yellow-400' : 'fill-gray-300'}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.05 3.231a1 1 0 00.95.69h3.4c.969 0 1.371 1.24.588 1.81l-2.75 2a1 1 0 00-.364 1.118l1.05 3.231c.3.921-.755 1.688-1.54 1.118l-2.75-2a1 1 0 00-1.176 0l-2.75 2c-.784.57-1.838-.197-1.539-1.118l1.05-3.231a1 1 0 00-.364-1.118l-2.75-2c-.783-.57-.38-1.81.588-1.81h3.4a1 1 0 00.951-.69l1.05-3.231z"/>
                  </svg>
                `).join('')}
              </div>
              <span class="text-sm text-gray-700">${product.rating}/5</span>
            </div>

            <!-- Renk adı -->
            <p id="${colorLabelId}" class="text-sm text-gray-500 mb-2">${capitalize(defaultColor)} Gold</p>

            <!-- Renk seçici -->
            <div class="flex gap-2 mt-2">
              ${colors.map(color => `
                <button
                  onclick="
                    document.getElementById('${imageId}').src='${product.images[color]}';
                    document.getElementById('${colorLabelId}').innerText='${capitalize(color)} Gold';
                  "
                  class="w-6 h-6 rounded-full border border-gray-300
                    ${color === 'yellow' ? 'bg-yellow-300' :
                      color === 'white' ? 'bg-gray-300' :
                      'bg-pink-300'}"
                ></button>
              `).join('')}
            </div>
          `;

          container.appendChild(card);
        });

        new Swiper('.mySwiper', {
          slidesPerView: 1,
          spaceBetween: 20,
          breakpoints: {
            640: { slidesPerView: 2 },
            1024: { slidesPerView: 4 }
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          }
        });
      });
  </script>
</body>
</html>
