import GridBlock from "./GridBlock";
import {Fragment} from "react";

const DashboardPage = () => {
	let blocks = cmplz_settings.blocks;
	return (
		<Fragment>
			{blocks.map((block, i) => <GridBlock block={block} key={i} />)}
		</Fragment>
	);

}
export default DashboardPage
