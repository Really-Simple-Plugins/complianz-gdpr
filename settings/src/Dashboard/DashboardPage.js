import GridBlock from "./GridBlock";
const DashboardPage = () => {
	let blocks = cmplz_settings.blocks;
	return (
		<>
			{blocks.map((block, i) => <GridBlock block={block} key={i} />)}
		</>
	);

}
export default DashboardPage
