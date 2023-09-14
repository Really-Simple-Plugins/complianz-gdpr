const Details = (initialLoadCompleted, cookies) => {
	return (
		<>
			{ initialLoadCompleted && cookies.map((cookie, i) => <div key={i}>{cookie}</div>)}
		</>
	)

}
export default Details;
