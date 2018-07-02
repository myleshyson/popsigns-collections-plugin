<?php use Aws\Common\Aws;

?>
<div id="app">
<?php $index = 0; ?>
<?php foreach ($order->get_items() as $key => $item) : ?>
<?php $data = wc_get_order_item_meta($key, 'vueform'); ?>

<?php
$images = collect([
    'light1' => $data['side1']['selected']['lightFull'],
    'dark1' => $data['side1']['selected']['darkFull'],
    'light2' => $data['side2']['selected']['lightFull'],
    'dark2' => $data['side2']['selected']['darkFull']
]);

if(!$images->contains('popsignsimages-937d.kxcdn.com')) {
    $images->map(function($image) {
        $data = base64_encode(file_get_contents($image));
        return 'data:image/png;base64,'.$data;
    });
} else {
    $credentials = new \Aws\Common\Credentials\Credentials(DBI_AWS_ACCESS_KEY_ID, DBI_AWS_SECRET_ACCESS_KEY);
    $client = \Aws\S3\S3Client::factory([
        'credentials' => $credentials
    ]);

    $client->registerStreamWrapper();

    if (!$data['isCustom']) {
        $images = $images->map(function ($image) {

            if (strpos($image, 'popsignsimages-937d.kxcdn.com/') !== false) {
                $key = str_replace(['popsignsimages-937d.kxcdn.com/', '//', 'https://', 'http://'], '/', $image);
                $path = "s3://popsigns-images{$key}";
                $data = base64_encode(file_get_contents($path));
                return 'data:image/png;base64,'.$data;
            } else {
                $replacing = str_replace(['http://', 'https://'], '', get_home_url());
                $path = rtrim(ROOT_DIR, '/').str_replace([$replacing, 'http://', 'https://', '/popsignsimages-937d.kxcdn.com'], '', $image);
                $data = base64_encode(file_get_contents($path));
                return 'data:'.mime_content_type($path).';base64,'.$data;
            }
        });
    }
}
?>
<h1>Order Item #<?php echo $data['productId']; ?></h1>
<?php
    $product = new \WC_Product($data['productId']);
?>
<download
    name="Side 1"
    :side="1 + '<?php echo $index; ?>'"
    :selected="<?php echo htmlspecialchars(json_encode($data['side1'])) ?>"
    light-image="<?php echo $images['light1']; ?>"
    dark-image="<?php echo $images['dark1']; ?>"
    is-custom="<?php echo $data['isCustom']; ?>"
    dark-hand-image="<?php echo $data['darkHandImage']; ?>"
    light-hand-image="<?php echo $data['lightHandImage']; ?>"
    order-id="<?php echo $order_id; ?>"
    product-title="<?php echo $product->get_name();  ?>"
></download>

<download
    name="Side 2"
    :side="2 + '<?php echo $index; ?>'"
    :selected="<?php echo htmlspecialchars(json_encode($data['side2'])) ?>"
    light-image="<?php echo $images['light2']; ?>"
    dark-image="<?php echo $images['dark2']; ?>"
    is-custom="<?php echo $data['isCustom']; ?>"
    dark-hand-image="<?php echo $data['darkHandImage']; ?>"
    light-hand-image="<?php echo $data['lightHandImage']; ?>"
    order-id="<?php echo $order_id; ?>"
    product-title="<?php echo $product->get_name();  ?>"
></download>
<?php $index++; ?>

<?php endforeach; ?>

</div>