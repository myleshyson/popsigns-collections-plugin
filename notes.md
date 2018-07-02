##Notes For Form Interaction

- Create text fields
- Color Fields
- Custom image background field
- Every sign asks for handwritign or block lettering (Maybe radio?)
- Logo Field
- Hashtag field
- Needs to be able to preload background image/color
- Needs to be able to preload text in select dropdown.


###Some possibilities

- Create Form For Sign

**Heading Field Here**

__Add a field + (Dropdown?)

-------------Once They Pick a Field------------------
####Sign Text Field
__Label: Select Your Text
__Underlined: ??
__Price Bump?
__Placeholder?

####Pre-made Options Field
__Lable:
__Placeholder:
__Option: Add option +
    --Image?
    --Price Bump?
    --Value?
    --Text?

####Colors Field
__Label:
__Placeholder:
__Color: Add Color +
    --RGB Value:
    --Name:
    --Price Bump?


####Logo Field
__Label:
__Placeholder:
__Image:
__Instructions:


####Hashtag Field
__Label:
__Placeholder:
__Text:
__Instructions:



    <compiled-canvas
        v-model="selected"
        :index="side"
        :light-image="lightImage"
        :dark-image="darkImage"
        :is-custom="isCustom"
        :hand-image="handImage">
      </compiled-canvas>


