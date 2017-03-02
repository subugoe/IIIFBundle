# IIIF Bundle

## Example configuration

```
subugoe_iiif:
  image:
    tile_width: 512
    tile_width: 512
    thumbnail_size: "92,"
    adapters:
      source:
        class: Ipf\Flysystem\Httpget\HttpGetAdapter
        configuration:
          base_uri: 'http://gdz.sub.uni-goettingen.de/tiff/'
      cache:
        class: League\Flysystem\Adapter\Local
        configuration: "%kernel.root_dir%/../var/images"
```
