<template>

    <div class="prediction"> <!-- если перевернули добавлять класс rotate_card -->
        <div class="prediction__inner">

            <div class="prediction__front">
                <div class="prediction__btn-wrap">
                    <button class="prediction__btn">Перевернуть</button>
                </div>
            </div>

            <div class="prediction__back">
                <div class="prediction-result">
                <div class="prediction-result__title">
                    У тебя все получится!
                </div>

                <div class="share">
                    <div class="share__title">
                        Поделиться в соцсетях
                    </div>

                    <div class="share__soc-net">
                        <button class="share__soc-net-link vk"></button>
                        <button class="share__soc-net-link facebook"></button>
                        <button class="share__soc-net-link twitter"></button>
                    </div>
                </div>

            </div>
            </div>

        </div>
    </div>

</template>

<script>
export default {

    name: "index"
}
</script>
