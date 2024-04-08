# IIIF Bundle

This is a Symfony 5.x/6.x Bundle to get an IIIF Representation out of arbitrary data structures.

## Example configuration

```
subugoe_iiif:
  image:
    http:
        scheme:           "https"
        host:             "images.example.com"
    tile_width:           512
    thumbnail_size:       "96,"
    zoom_levels:          [1, 2, 4, 8, 16]
  presentation:
    logo:                 "http://service.example.com/fileadmin/gdz/layout/head_logo.jpg"
    service_id:           "http://service.exmple.com/"
    http:
        scheme:           "https"
        host:             "manifests.example.com"
```

## Format translator

It is also necessary to define a so-called translator-service with the name ```subugoe_iiif.translator```, i.e.:

```yaml
  Subugoe\IIIFBundle\Translator\TranslatorInterface:
    class:      Subugoe\IIIFBundle\Translator\SubugoeTranslator
    arguments:  ["@subugoe_find.search_service"]
```

The translator has to implement the ```Subugoe\IIIFBundle\Translator\TranslatorInterface```. An example translator is bundled,
see the SubugoeTranslator class.

## File systems

This bundle uses [Flysystem](http://flysystem.thephpleague.com/), for retrieving and storing (a.k.a. caching) the tiles and thumbnails.
If caching should not be enabled the `Null Adapter` should be used.
Please have a look at the FlysystemBundle documentation for the configuration options and available bundles.
The adapter that fits to your needs has to be required in the main composer manifest of your application.

Please note, that two flysystem services need to exist and one has to be named `cache_filesystem` to be used as cache filesystem.
The source filesystem (where the original scans reside) has to be aliased or named `source_filesystem`.
