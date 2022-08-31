import { registerBlockType } from "@wordpress/blocks";
import { InspectorControls, useBlockProps, Fragment } from "@wordpress/block-editor";
import { SelectControl } from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";
import { useEffect } from "@wordpress/element";


registerBlockType("gtgt-block/get-post-data", {
  title: "Please select any Post",
  icon: "smiley",
  attributes: {
    selectOptions: {
      type: "object",
    },
    postData: { type: "object" },
    post_id: { type: "string" },
  },
  edit: (props) => {
    const { attributes, setAttributes } = props;
    const blockProps = useBlockProps();
    useEffect(() => {
      var postsCollection = new wp.api.collections.Posts();
      postsCollection.fetch().done(function (posts) {
        var options = [];
        options.push({ label: "Please Select Post", value: "0" });
        posts.forEach((post) => {
          options.push({ label: post.title.rendered, value: post.id });
        });

        setAttributes({ selectOptions: options });
      });
    }, []);
    const changeSelectedPost = (post_id) => {
      setAttributes({ post_id: post_id });

      // POST
      apiFetch({
        path: `post_data/v1/post_detail/${post_id}`,
        method: "GET",
      }).then((result) => {
        setAttributes({ postData: result });
      });
    };
    return (
      <div class="gtgt-block-main-div" {...blockProps}>
        {/* <Fragment> */}
            <InspectorControls key="setting">
            <div id="nes-select-controls">
                <SelectControl
                label="Please Select Post"
                value={
                    props.attributes.postData ? props.attributes.postData.id : ""
                }
                options={props.attributes.selectOptions}
                onChange={changeSelectedPost}
                />
            </div>
            </InspectorControls>
        {/* </Fragment> */}
        {attributes.postData ? (
          <div>
            <div class="gtgt-block-box-col">
              <div class="gtgt-block-main-box">
                <h2>{attributes.postData.post_title}</h2>

                {attributes.postData.feature_image != "" ? (
                    <img src={attributes.postData.feature_image}/>
                ) : ("")
                }

                <p>
                {attributes.postData.post_content != ""
                    ? attributes.postData.post_content
                    : "-"}
                </p>
              </div>
            </div>
          </div>
        ) : (
          ""
        )}
      </div>
    );
  },
  save: (props) => {
    const { attributes, setAttributes } = props;
    return (
      <div>
        {attributes.postData ? (
          <div class="gtgt-block-main-div">
            <div class="gtgt-block-box-col">
              <div class="gtgt-block-main-box">
                <h2>{attributes.postData.post_title}</h2>

                {attributes.postData.feature_image != "" ? (
                    <img src={attributes.postData.feature_image}/>
                ) : ("")
                }
                <p>
                {attributes.postData.post_content != ""
                ? attributes.postData.post_content
                : "-"}
                </p>
              </div>
            </div>
          </div>
        ) : (
          ""
        )}
      </div>
    );
  },
});
