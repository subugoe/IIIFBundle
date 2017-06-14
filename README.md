# IIIF Bundle

This is a Symfony 3.x Bundle to get an IIIF Representation out of arbitrary data structures.

## Example configuration

```
subugoe_iiif:
  image:
    http:
        scheme:           "https"
        host:             "images.example.com"
    tile_width:           512
    tile_width:           512
    thumbnail_size:       "96,"
    zoom_levels:          [1, 2, 4, 8, 16]
    adapters:
      source:
        class:            Ipf\Flysystem\Httpget\HttpGetAdapter
        configuration:
          base_uri:       "http://gdz.sub.uni-goettingen.de/tiff/"
  presentation:
    logo:                 "http://gdz.sub.uni-goettingen.de/fileadmin/gdz/layout/head_logo.jpg"
    service_id:           "http://gdzdev.sub.uni-goettingen.de/"
    http:
        scheme:           "https"
        host:             "images.example.com"
```

## Format translator

It is also necessary to define a so-called translator-service with the name ```subugoe_iiif.translator```, i.e.:
```
  subugoe_iiif.translator:
    class:      Subugoe\IIIFBundle\Translator\SubugoeTranslator
    arguments:  ["@subugoe_find.search_service"]
```

The translator has to implement the ```Subugoe\IIIFBundle\Translator\TranslatorInterface```. An example translator is bundled,
see the SubugoeTranslator class.

## File systems

This bundle uses [Flysystem](http://flysystem.thephpleague.com/), for retrieving and storing (a.k.a. caching) the tiles and thumbnails.
If caching should not be enabled the `Null Adapter` should be used. The [FlysystemBundle](https://github.com/1up-lab/OneupFlysystemBundle) is already required in the composer manifest.
Please have a look at the FlysystemBundle documentation for the configuration options and available bundles.
The adapter that fits to your needs has to be required in the main composer manifest of your application.

For caching, a flysystem configuration has to be enabled like that:

```
oneup_flysystem:
    adapters:
        cache_adapter:
            awss3v3:
                client: foo.s3_client
                bucket: 'foo'
                prefix: 'cache'

    filesystems:
        subugoe_iiif_cache:
            adapter: cache_adapter
            alias: cache_filesystem
```

Please note, that it has to be named `cache_filesystem` to be used.
